<?php

namespace App\Http\Controllers;

use App\Timesheet;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;

class TimesheetController extends Controller
{
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

    protected function formatArray($array, $daterange){
        $formattedArray = array();
        for($i = 0; $i < 14; $i++){
            $formattedArray[$daterange[$i]] = $array[$i];
        }
        return $formattedArray;
    }

    protected function getDate(){
        $time = date("Y-m-d H:i:s");
        $php_date = new \DateTime($time, new \DateTimeZone('America/Chicago'));
        //$date = new UTCDateTime($php_date->getTimestamp() * 1000);
        return $php_date;
    }

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

    protected function erase_last_2_weeks($array, $daterangeArray){
        foreach($daterangeArray as $date){
            if(isset($array[$date])){
                unset($array[$date]);
            }
        }
        return $array;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date)
    {
        return view('pages.timesheet', compact('date'));
    }

    public function timesheetSave(Request $request, $id = null){
        $timesheet = Timesheet::find($id);
        if($timesheet){
            $this->store($timesheet, $request);
        }
        else{
            $timesheet = new Timesheet();
            $timesheet->user = auth()->user()->email;
            $this->store($timesheet, $request);
        }
        return redirect('/projectindex');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($timesheet, $request)
    {   if($timesheet['Codes']){                //Enter this if there was a previous timesheet
            //dd($request);
            $codes = $timesheet['Codes'];
            if(isset($codes['Additional_Codes'])){
                unset($codes['Additional_Codes']);
            }

            //Store code CEG
            $daterangeArray = $request->get('daterange');
            $CEG = array();
            $CEG['General and Admin'] = $this->databasePrep($this->formatArray($request->get('row0'), $daterangeArray));
            $CEG['General and Admin'] += $this->arrayFormat($CEG['General and Admin'], $timesheet['Codes']['CEG']['General and Admin'], $daterangeArray);

            $CEG['Staff Meetings and HR'] = $this->databasePrep($this->formatArray($request->get('row1'), $daterangeArray));
            $CEG['Staff Meetings and HR'] += $this->arrayFormat($CEG['Staff Meetings and HR'], $timesheet['Codes']['CEG']['Staff Meetings and HR'], $daterangeArray);

            // if(isset($timesheet['Codes']['CEG']) && count($timesheet['Codes']['CEG']) > 2){
            //     $CEG_keys = array_keys($timesheet['Codes']['CEG']);
            //     foreach($CEG_keys as $CEG_additional){
            //         if(!array_key_exists($CEG_additional, $CEG)){
            //             $CEG[$CEG_additional] = $timesheet['Codes']['CEG'][$CEG_additional];
            //         }
            //     }
            // }
            $codes['CEG'] = $CEG;

            //Store Code CEGTRNG
            $CEGTRNG = array();
            $CEGTRNG['Research and Training'] = $this->databasePrep($this->formatArray($request->get('row2'), $daterangeArray));
            $CEGTRNG['Research and Training'] += $this->arrayFormat($CEGTRNG['Research and Training'], $timesheet['Codes']['CEGTRNG']['Research and Training'], $daterangeArray);
            $codes['CEGTRNG'] = $CEGTRNG;

            //Store Code CEGEDU
            $CEGEDU = array();
            $CEGEDU['Formal EDU'] = $this->databasePrep($this->formatArray($request->get('row3'), $daterangeArray));
            $CEGEDU['Formal EDU'] += $this->arrayFormat($CEGEDU['Formal EDU'], $timesheet['Codes']['CEGEDU']['Formal EDU'], $daterangeArray);
            $codes['CEGEDU'] = $CEGEDU;

            //Store Code CEGMKTG
            $CEGMKTG = array();
            $CEGMKTG['General Marketing'] = $this->databasePrep($this->formatArray($request->get('row4'), $daterangeArray));
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
            if($row > 4) {
                $arrayCodes = array();
                $descriptions = array();
                for($i = 5; $i <= $row; $i++){
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
            $CEG['General and Admin'] = $this->databasePrep($this->formatArray($request->get('row0'), $daterangeArray));
            $CEG['Staff Meetings and HR'] = $this->databasePrep($this->formatArray($request->get('row1'), $daterangeArray));
            $codes['CEG'] = $CEG;

            //Store Code CEGTRNG
            $CEGTRNG = array();
            $CEGTRNG['Research and Training'] = $this->databasePrep($this->formatArray($request->get('row2'), $daterangeArray));
            $codes['CEGTRNG'] = $CEGTRNG;

            //Store Code CEGEDU
            $CEGEDU = array();
            $CEGEDU['Formal EDU'] = $this->databasePrep($this->formatArray($request->get('row3'), $daterangeArray));
            $codes['CEGEDU'] = $CEGEDU;

            //Store Code CEGMKTG
            $CEGMKTG = array();
            $CEGMKTG['General Marketing'] = $this->databasePrep($this->formatArray($request->get('row4'), $daterangeArray));
            $codes['CEGMKTG'] = $CEGMKTG;

            //Added rows
            $row = (int) $request->get('row_total');
            if($row > 4) {
                $arrayCodes = array();
                $descriptions = array();
                for($i = 5; $i <= $row; $i++){
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

    public function check()
    {
        $date = $this->getDate();
        $collection = Timesheet::where('user', auth()->user()->email)->get();
        if(!$collection->isEmpty()){
            $timesheet = $collection[0];
            return $this->edit($timesheet, $date);
        }
        else{
            return $this->index($date);
        }
    }

    public function edit($timesheet, $date)
    {
        return view('pages.timesheet', compact('timesheet', 'date'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }
}
