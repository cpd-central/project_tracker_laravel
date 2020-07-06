<?php

namespace App\Http\Controllers;

use App\Timesheet;
use App\User;
use Illuminate\Http\Request;

use DateInterval;
use DatePeriod;

class TimesheetController extends Controller
{
    /**
     * Prepares the Array of dates to be saved to database.
     * If the date is null, blank, or 0, it is taken out.
     * @return $array
     */
    protected function databasePrep($array){
        if($array){
            foreach($array as $key => $day){
              if($day == null || $day =="" || $day == 0){
                unset($array[$key]);
              }
            }
          array_walk($array, function(&$x){$x = (float)($x);});
        }
        return $array;
    }

    /**
     * Assigns dates with their corresponding hours.
     * @return $formattedArray
     */
    protected function formatArray($array, $daterange){
        $formattedArray = array();
        for($i = 0; $i < count($array); $i++){
            $formattedArray[$daterange[$i]] = $array[$i];
        }
        return $formattedArray;
    }

    /**
     * Gets todays date to determine the date range when rendering the timesheet page.
     * @return $php_date
     */
    public function getDate(){
        $time = date("Y-m-d H:i:s");
        $php_date = new \DateTime($time, new \DateTimeZone('America/Chicago'));
        return $php_date;
    }

    /**
     * Combines the old Timesheet data with the new so that it keeps all data across time.
     * @return array reformatted new array with old data.
     */
    protected function arrayFormat($new_array, $old_array, $daterangeArray){
        foreach($daterangeArray as $date){
            if((!isset($new_array[$date])) && isset($old_array[$date])){
                unset($old_array[$date]);
            }
            elseif((isset($new_array[$date])) && isset($old_array[$date])){
                $old_array[$date] = $new_array[$date];
            }
        }
        return $old_array;
    }

    /**
     * Gets rid of the code in the last 2 weeks because its old data. Will get readded if its still in the
     * request. This helps overwrite new data with old data.
     * @return array
     */
    protected function erase_last_2_weeks($array, $daterangeArray){
        foreach($daterangeArray as $date){
            if(isset($array[$date])){ 
                unset($array[$date]);
            }
        }
        return $array;
    }
    
    /**
     * Gets all users and timesheets for timesheet sent status page.
     * @return view pages.timesheetsentstatus
     */
	protected function get_user_timesheet_status(){
        $timesheets = Timesheet::all();
        $not_active_users = User::all()->where('active', false);
        $non_active_array = [];
        foreach($not_active_users as $non){
            array_push($non_active_array, $non['email']);
        }
        $i = 0;
        foreach($timesheets as $timesheet){
            if(in_array($timesheet['user'], $non_active_array)){
                unset($timesheets[$i]);
            }
            $i++;
        }
        $users = User::all()->where('active', true);

        //$users = User::all();
		return view('pages.timesheetsentstatus', compact('timesheets','users'));
	}

    /**
     * Returns the timesheet view with the data compacted so
     * the date range can be created.
     * @return view pages.timesheet
     */
     public function index($date, $reference_list, $arr, $header_arr, $start, $end)
    {
        return view('pages.timesheet', compact('date', 'reference_list', 'arr', 'header_arr', 'start', 'end'));
    }

    /**
     * gets dates for timesheetSave method.
     * @return Array
     */
    public function get_dates($start, $end)
    {
        $start->setTime(0,0,0);
        $end->setTime(0,0,1);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end);
        $arr = array();
        $header_arr = array();
        foreach($period as $dt)
        {
            array_push($arr, $dt->format('j-M-y'));
            array_push($header_arr, $dt->format('D j-M-y'));
        }
        return array($arr, $header_arr);
    }

    /**
     * Checks to see if there are code/description duplicates on the Timesheet.
     * @return Boolean
     */
    protected function duplicate_code_descriptions($req, $num_rows) {
        $codes_descriptions = array();
        for ($i=7; $i<=$num_rows; $i++) {
            $code = $req->get('codeadd'.$i);
            $description = $req->get('Product_Description_row_'.$i); 
            #push the string "<CODE>, <DESCRIPTION>" into the array.  this way we can compare them 
            array_push($codes_descriptions, $code . ", " . $description);
        } 
        //check for duplicates
        if (count($codes_descriptions) !== count(array_unique($codes_descriptions))) {
            //if the count is different, there are duplicates so return true  
            return True; 
        }
        else {
            return False;
        } 
    }
    
    /**
     * Determines if there's a timesheet saved or not. Stores the timesheet or creates a new one to be stored
     * with a message to notify the user it was successfully saved to the database.
     * @return $this->check($message)
     */
    public function timesheetSave(Request $request, $id = null){
        //check if we have more than 6 rows and if so, we check for duplicates
        $row = (int) $request->get('row_total');
        if($row > 6) { 
            $has_duplicates = $this->duplicate_code_descriptions($request, $row);
        } 
        else {
            $has_duplicates = False;
        }

        $action = $request->input('action');
        $start_date = $request['startdate']; 
        $end_date = $request['enddate'];
        $today = $this->getDate();
        $og_end = clone $today;
        $og_start = $today->sub(new DateInterval('P13D'));
        $og_date_range = $this->get_dates($og_start, $og_end)[0]; 
               
        if ($start_date == null and $end_date == null) {
            $message = null; 
            $start = $og_start; 
            $end = $og_end; 
            $arrays = $this->get_dates($start, $end); 
            $arr = $arrays[0];
            $header_arr = $arrays[1];
        } 
        else { 
            $start = new \DateTime($request['startdate'], new \DateTimeZone('America/Chicago'));
            $end = new \DateTime($request['enddate'], new \DateTimeZone('America/Chicago')); 
            $difference = $end->diff($start)->format("%a") + 1; 
            if ($difference > 14) {
                $start = $og_start;
                $end = $og_end; 
                $arr = $this->get_dates($start, $end)[0];
                $header_arr = $this->get_dates($start, $end)[1];
                $message = "Date Range must be 14 days or fewer.";
            }
            else if ($start > $end) {
                $start = $og_start;
                $end = $og_end; 
                $arr = $this->get_dates($start, $end)[0];
                $header_arr = $this->get_dates($start, $end)[1];
                $message = "End Date must be after Start Date.";
            } 
            else {
                $message = null; 
                $arr = $this->get_dates($start, $end)[0];  
                $header_arr = $this->get_dates($start, $end)[1];
            } 
            //we also need the original 14 day start and end
        } 
       
        if ($action == 'date_reset') {
            //reset the start and end dates to null if this was the button clicked 
            $start = $og_start;
            $end = $og_end; 
            $arr = $this->get_dates($start, $end)[0];
            $header_arr = $this->get_dates($start, $end)[1];
            return $this->check($start, $end, $arr, $header_arr, $message = null);
        }
        else if ($action == 'date_range') {
            //check if the date range is greater than 14 days 
            return $this->check($start, $end, $arr, $header_arr, $message);            
        }
        else if ($action == 'submit') {
            if ($has_duplicates) {
                $start = $og_start;
                $end = $og_end;
                $arr = $this->get_dates($start, $end)[0];
                $header_arr = $this->get_dates($start, $end)[1]; 
                $message = "You have one or more duplicate code, description pair(s).  Please fix and re-submit.";
                return $this->check($start, $end, $arr, $header_arr, $message);
            } 
            
            $collection = Timesheet::where('user', auth()->user()->email)->get(); 

            if(!$collection->isEmpty()){
                $timesheet = $collection[0]; 
                $this->store($timesheet, $request, $og_date_range);
            }
            else{
                $timesheet = new Timesheet();
                $timesheet->user = auth()->user()->email;
                $timesheet->pay_period_sent = True; 
                $this->store($timesheet, $request, $og_date_range);
            }
            $message = "Success! Timesheet was saved.";
            return $this->check($start, $end, $arr, $header_arr, $message); 
        }
        #return $this->check();
    }

    /**
     * Stores the Timesheet to the database.
     * @param $timesheet - timesheet to be saved to database.
     * @param Request $request
     * @param $og_date_range - original date range.
     */
    public function store($timesheet, $request, $og_date_range)
    {   
        if($timesheet['Codes']){                //Enter this if there was a previous timesheet

            $codes = $timesheet['Codes'];
            if(isset($codes['Additional_Codes'])){
                unset($codes['Additional_Codes']);
            }

            //Store code CEG
            $daterangeArray = $request->get('daterange');
            $CEG = array();
            $CEG['Holiday'] = $this->databasePrep($this->formatArray($request->get('row0'), $daterangeArray));
            $CEG['Holiday'] += $this->arrayFormat($CEG['Holiday'], $timesheet['Codes']['CEG']['Holiday'], $daterangeArray);           

            $CEG['PTO'] = $this->databasePrep($this->formatArray($request->get('row1'), $daterangeArray));
            $CEG['PTO'] += $this->arrayFormat($CEG['PTO'], $timesheet['Codes']['CEG']['PTO'], $daterangeArray);
            
            $CEG['General and Admin'] = $this->databasePrep($this->formatArray($request->get('row2'), $daterangeArray));
            $CEG['General and Admin'] += $this->arrayFormat($CEG['General and Admin'], $timesheet['Codes']['CEG']['General and Admin'], $daterangeArray);

            $CEG['Staff Meetings and HR'] = $this->databasePrep($this->formatArray($request->get('row3'), $daterangeArray));
            $CEG['Staff Meetings and HR'] += $this->arrayFormat($CEG['Staff Meetings and HR'], $timesheet['Codes']['CEG']['Staff Meetings and HR'], $daterangeArray);

            $codes['CEG'] = $CEG;

            //Store Code CEGTRNG
            $CEGTRNG = array();
            $CEGTRNG['Research and Training'] = $this->databasePrep($this->formatArray($request->get('row4'), $daterangeArray));
            $CEGTRNG['Research and Training'] += $this->arrayFormat($CEGTRNG['Research and Training'], $timesheet['Codes']['CEGTRNG']['Research and Training'], $daterangeArray);
            $codes['CEGTRNG'] = $CEGTRNG;

            //Store Code CEGEDU
            $CEGEDU = array();
            $CEGEDU['Formal EDU'] = $this->databasePrep($this->formatArray($request->get('row5'), $daterangeArray));
            $CEGEDU['Formal EDU'] += $this->arrayFormat($CEGEDU['Formal EDU'], $timesheet['Codes']['CEGEDU']['Formal EDU'], $daterangeArray);
            $codes['CEGEDU'] = $CEGEDU;

            //Store Code CEGMKTG
            $CEGMKTG = array();
            $CEGMKTG['General Marketing'] = $this->databasePrep($this->formatArray($request->get('row6'), $daterangeArray));
            $CEGMKTG['General Marketing'] += $this->arrayFormat($CEGMKTG['General Marketing'], $timesheet['Codes']['CEGMKTG']['General Marketing'], $daterangeArray);
            $codes['CEGMKTG'] = $CEGMKTG;

            
            //Store old additional codes EXCEPT LAST TWO WEEKS

            if(isset($timesheet['Codes']["Additional_Codes"])){
                $codes["Additional_Codes"] = $timesheet['Codes']["Additional_Codes"];
                $code_keys = array_keys($codes["Additional_Codes"]); 
                foreach($code_keys as $key){
                    foreach($codes["Additional_Codes"] as $add_code){
                        for($i=0; $i < count($add_code); $i++){
                            if(isset($timesheet['Codes'][$key][$add_code[$i]])){ 
                                $codes[$key][$add_code[$i]] = $timesheet['Codes'][$key][$add_code[$i]];
                                $codes[$key][$add_code[$i]] = $this->erase_last_2_weeks($codes[$key][$add_code[$i]], $daterangeArray);

                                if(count($codes[$key][$add_code[$i]]) == 0){
                                    unset($codes[$key][$add_code[$i]]);
                                    //unset($codes["Additional_Codes"][$key]);
                                }
                                if($key != "CEG" && $key != "CEGTRNG" && $key != "CEGEDU" && $key != "CEGMKTG"){
                                    if(count($codes[$key]) == 0){
                                        unset($codes[$key]);
                                    }
                                }
                                unset($codes["Additional_Codes"][$key]);
                            }
                        }
                    }
                 }
                 unset($codes["Additional_Codes"]);
             }

            //Added rows
            $row = (int) $request->get('row_total');
            if($row > 6) { 
                $arrayCodes = array(); 
                $descriptions = array();
                for($i = 7; $i <= $row; $i++){ 
                    if($request->get('row'.$i) != null){ 
                        $arr = array();
                        $string = $request->get('Product_Description_row_'.$i);
                        $code = $request->get('codeadd'.$i);
                        $arr[$string] = $this->databasePrep($this->formatArray($request->get('row'.$i), $daterangeArray));
                        if(array_sum($request->get('row'.$i)) > 0){
                            if(isset($timesheet['Codes'][$code][$string])){
                                $arr[$string] += $this->arrayFormat($arr[$string], $timesheet['Codes'][$code][$string], $daterangeArray);
                            }
                            if(array_key_exists($code, $arrayCodes)){
                                $descriptions = $arrayCodes[$code];
                                array_push($descriptions, $string);
                                $arrayCodes[$code] = $descriptions;
                            }
                            else{
                                $descriptions = array();
                                array_push($descriptions, $string);
                                $arrayCodes[$code] = $descriptions;
                            }
                            if(isset($codes[$code])){
                                $codes[$code] = array_merge($codes[$code], $arr);   //might need to be fixed in future with edit
                            }
                            else{
                                $codes[$code] = $arr;
                            }
                        }
                        else {
                            // now we have to check if there is data in the original date range and keep those codes too                          
                            foreach ($og_date_range as $date) {
                                if (array_key_exists($date, $codes[$code][$string])) {
                                    if(array_key_exists($code, $arrayCodes)) {
                                        $descriptions = $arrayCodes[$code];
                                        array_push($descriptions, $string);
                                        $arrayCodes[$code] = $descriptions;
                                    }
                                    else {
                                        $descriptions = array();
                                        array_push($descriptions, $string);
                                        $arrayCodes[$code] = $descriptions;
                                    }
                                    #break loop if we find a match
                                    break;
                                }
                            }
                        }
                    }
                }
                if(count($arrayCodes) > 0){
                    $Additional_Codes = $arrayCodes;
                    $codes["Additional_Codes"] = $Additional_Codes;
                }
            }
            $timesheet->Codes = $codes;
            $timesheet->save();
        }
        else{    //If there wasn't a previous timesheet, don't need to worry about overwriting.
            $codes = array();
            //Store code CEG
            $daterangeArray = $request->get('daterange');
            $CEG = array();
            $CEG['Holiday'] = $this->databasePrep($this->formatArray($request->get('row0'), $daterangeArray)); 
            $CEG['PTO'] = $this->databasePrep($this->formatArray($request->get('row1'), $daterangeArray));
            $CEG['General and Admin'] = $this->databasePrep($this->formatArray($request->get('row2'), $daterangeArray));
            $CEG['Staff Meetings and HR'] = $this->databasePrep($this->formatArray($request->get('row3'), $daterangeArray));
            $codes['CEG'] = $CEG;

            //Store Code CEGTRNG
            $CEGTRNG = array();
            $CEGTRNG['Research and Training'] = $this->databasePrep($this->formatArray($request->get('row4'), $daterangeArray));
            $codes['CEGTRNG'] = $CEGTRNG;

            //Store Code CEGEDU
            $CEGEDU = array();
            $CEGEDU['Formal EDU'] = $this->databasePrep($this->formatArray($request->get('row5'), $daterangeArray));
            $codes['CEGEDU'] = $CEGEDU;

            //Store Code CEGMKTG
            $CEGMKTG = array();
            $CEGMKTG['General Marketing'] = $this->databasePrep($this->formatArray($request->get('row6'), $daterangeArray));
            $codes['CEGMKTG'] = $CEGMKTG;

            //Added rows
            $row = (int) $request->get('row_total');
            if($row > 6) {
                $arrayCodes = array();
                $descriptions = array();
                for($i = 7; $i <= $row; $i++){
                    if($request->get('row'.$i) != null){
                        if(array_sum($request->get('row'.$i)) > 0){
                            $arr = array();
                            $string = $request->get('Product_Description_row_'.$i);
                            $arr[$string] = $this->databasePrep($this->formatArray($request->get('row'.$i), $daterangeArray));
                            $code = $request->get('codeadd'.$i);

                            if(array_key_exists($code, $arrayCodes)){
                                $descriptions = $arrayCodes[$code];
                                array_push($descriptions, $string);
                                $arrayCodes[$code] = $descriptions;
                            }
                            else{
                                $descriptions = array();
                                array_push($descriptions, $string);
                                $arrayCodes[$code] = $descriptions;
                            }
                            if(isset($codes[$code])){
                                $codes[$code] = array_merge($codes[$code], $arr);   //might need to be fixed in future with edit
                            }
                            else{
                                $codes[$code] = $arr;
                            }
                        }
                    }
                }
                if(count($arrayCodes) > 0){
                    $Additional_Codes = $arrayCodes;
                    $codes["Additional_Codes"] = $Additional_Codes;
                }
            }
            $timesheet->Codes = $codes;
            $timesheet->save();
        }
    }

    /**
     * Checks to see if the user has a timesheet already.
     * @parameter $message for notifying the user the timesheet saved.
     * @return view pages.timesheet
     */
    public function check($start, $end, $arr = null, $header_arr = null, $message = null)
    {
        if (!isset($arr) && !isset($header_arr))
        {
            $today = $this->getDate(); 
            $end = clone $today;
            $start = $today->sub(new DateInterval('P13D'));
            $arr = $this->get_dates($start, $end)[0];
            $header_arr = $this->get_dates($start, $end)[1];
        } 
        $date = $this->getDate();
        
        $collection = Timesheet::where('user', auth()->user()->email)->get(); 
        $reference_list = Timesheet::where('name', 'reference_list')->get(); //Only works on production
        if(!$collection->isEmpty()){
            $timesheet = $collection[0];
            return $this->edit($timesheet, $date, $message, $reference_list, $arr, $header_arr, $start, $end);
        }
        else{
            return $this->index($date, $reference_list, $arr, $header_arr, $start, $end);
        }
    }

    /**
     * Returns the timesheet view with the data compacted so
     * the date range can be created, the timesheet to be edited, and message if it was just saved.
     * @parameter $timesheet, $date, $message
     * @return view pages.timesheet
     */
    public function edit($timesheet, $date, $message = null, $reference_list, $arr, $header_arr, $start, $end)
    {
        return view('pages.timesheet', compact('timesheet', 'date', 'message', 'reference_list', 'arr', 'header_arr', 'start', 'end'));
    }

    public function drafter_hours()
    {
        return view('pages.drafterhours');
    }
}
