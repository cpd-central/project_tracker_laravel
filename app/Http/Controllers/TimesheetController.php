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

    // protected function getArrayOfDates($string){
    //     $monthArray = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    //     $str_arr = explode ("/", $string);
    //     $arrayOfDates = array();
    //     //array_push($arrayOfDates, $str_arr[0]);
    //     $startDateArr = explode ("-", $str_arr[0]);
    //     //$endDateArr = explode ("-", $str_arr[1]);
    //     $dayNum = $startDateArr[0];
    //     $month = $startDateArr[1];
    //     for($i = 0; $i < 14; $i++){
    //         $dateString = $dayNum.'-'.$month;
    //         array_push($arrayOfDates, $dateString);
    //         $dayNum += 1;
    //         if($month == 'Jan' || $month == 'Mar' || $month == 'May' 
    //         || $month == 'Jul' || $month == 'Aug' || $month == 'Oct' 
    //         || $month == 'Sep'){
    //             if($dayNum > 31){
    //                 $dayNum = 1;
    //                 $key = array_search($month, $monthArray);
    //                 $month = $monthArray[$key + 1];
    //             }
    //         }
    //     }
    //     return $arrayOfDates;
    //}

    protected function getDate(){
        $time = date("Y-m-d H:i:s");
        $php_date = new \DateTime($time, new \DateTimeZone('America/Chicago'));
        //$date = new UTCDateTime($php_date->getTimestamp() * 1000);
        return $php_date;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $timesheet = new Timesheet();
        $timesheet->user = auth()->user()->email;
        $this->store($timesheet, $request);
        return redirect('/projectindex');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($timesheet, $request)
    {   $codes = array();
        //Store code CEG
        $daterangeArray = $request->get('daterange');
        $CEG = array();
        $CEG['General and Admin'] = $this->databasePrep($this->formatArray($request->get('row0'), $daterangeArray));
        $CEG['Staff Meetings and HR'] = $this->databasePrep($this->formatArray($request->get('row1'), $daterangeArray));
        $codes['CEG'] = $CEG;
        //$timesheet->CEG = $CEG;

        //Store Code CEGTRNG
        $CEGTRNG = array();
        $CEGTRNG['Research and Training'] = $this->databasePrep($this->formatArray($request->get('row2'), $daterangeArray));
        $codes['CEGTRNG'] = $CEGTRNG;
        //$timesheet->CEGTRNG = $CEGTRNG;

        //Store Code CEGEDU
        $CEGEDU = array();
        $CEGEDU['Formal EDU'] = $this->databasePrep($this->formatArray($request->get('row3'), $daterangeArray));
        $codes['CEGEDU'] = $CEGEDU;
        //$timesheet->CEGEDU = $CEGEDU;

        //Store Code CEGMKTG
        $CEGMKTG = array();
        $CEGMKTG['General Marketing'] = $this->databasePrep($this->formatArray($request->get('row4'), $daterangeArray));
        $codes['CEGMKTG'] = $CEGMKTG;
        //$timesheet->CEGMKTG = $CEGMKTG;

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
                            array_merge($codes[$code], $arr);   //might need to be fixed in future with edit
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timesheet $timesheet)
    {
    
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
