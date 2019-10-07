<?php

namespace App\Http\Controllers;

use App\Timesheet;
use Illuminate\Http\Request;

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
        for($i = 0; $i < 14; $i++){
            $formattedArray[$daterange[$i]] = $array[$i];
        }
        return $formattedArray;
    }

    /**
     * Gets todays date to determine the date range when rendering the timesheet page.
     * @return $php_date
     */
    protected function getDate(){
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
     * Returns the timesheet view with the data compacted so
     * the date range can be created.
     * @return view pages.timesheet
     */
    public function index($date, $reference_list)
    {
        return view('pages.timesheet', compact('date', 'reference_list'));
    }

    /**
     * Determines if there's a timesheet saved or not. Stores the timesheet or creates a new one to be stored
     * with a message to notify the user it was successfully saved to the database.
     * @return $this->check($message)
     */
    public function timesheetSave(Request $request, $id = null){
        $timesheet = Timesheet::find($id);
        if($timesheet){
            $this->store($timesheet, $request);
        }
        else{
            $timesheet = new Timesheet();
            $timesheet->user = auth()->user()->email;
            $timesheet->pay_period_sent = True; 
            $this->store($timesheet, $request);
        }
        $message = "Success! Timesheet was saved.";
        return $this->check($message);
    }

    /**
     * Stores the Timesheet to the database.
     */
    public function store($timesheet, $request)
    {   
        if($timesheet['Codes']){                //Enter this if there was a previous timesheet
            //dd($request);
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
            dd($timesheet); 
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
                        if(array_sum($request->get('row'.$i)) > 0){
                            $arr = array();
                            $string = $request->get('Product_Description_row_'.$i);
                            $code = $request->get('codeadd'.$i);
                            $arr[$string] = $this->databasePrep($this->formatArray($request->get('row'.$i), $daterangeArray));
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
        else{                       //If there wasn't a previous timesheet, don't need to worry about overwriting.
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
    public function check($message = null)
    {
        $date = $this->getDate();

        $collection = Timesheet::where('user', auth()->user()->email)->get(); 
        $reference_list = Timesheet::where('name', 'reference_list')->get(); //Only works on production
        if(!$collection->isEmpty()){
            $timesheet = $collection[0];
            return $this->edit($timesheet, $date, $message, $reference_list);
        }
        else{
            return $this->index($date, $reference_list);
        }
    }

    /**
     * Returns the timesheet view with the data compacted so
     * the date range can be created, the timesheet to be edited, and message if it was just saved.
     * @parameter $timesheet, $date, $message
     * @return view pages.timesheet
     */
    public function edit($timesheet, $date, $message = null, $reference_list)
    {
        return view('pages.timesheet', compact('timesheet', 'date', 'message', 'reference_list'));
    }
}
