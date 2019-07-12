<?php

namespace App\Http\Controllers;

use App\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    protected function databasePrep($array, $arrayOfDates){
        if($array){
            foreach($array as $day){
              if($day == null || $day ==""){
                $day = 0;
              }
            }
          array_walk($array, function(&$x){$x = (float)($x);});
        }
        $formattedArray = array();
        for($i = 0; $i < 14; $i++){
            $formattedArray[$arrayOfDates[$i]] = $array[$i];
        }
        return $formattedArray;
    }

    protected function getArrayOfDates($string){
        $monthArray = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $str_arr = explode ("/", $string);
        $arrayOfDates = array();
        //array_push($arrayOfDates, $str_arr[0]);
        $startDateArr = explode ("-", $str_arr[0]);
        //$endDateArr = explode ("-", $str_arr[1]);
        $dayNum = $startDateArr[0];
        $month = $startDateArr[1];
        for($i = 0; $i < 14; $i++){
            $dateString = $dayNum.'-'.$month;
            array_push($arrayOfDates, $dateString);
            $dayNum += 1;
            if($month == 'Jan' || $month == 'Mar' || $month == 'May' 
            || $month == 'Jul' || $month == 'Aug' || $month == 'Oct' 
            || $month == 'Sep'){
                if($dayNum > 31){
                    $dayNum = 1;
                    $key = array_search($month, $monthArray);
                    $month = $monthArray[$key + 1];
                }
            }
        }
        return $arrayOfDates;
    }

    protected function getDates(){
        $time = date("Y-m-d H:i:s");
        $php_date = new \DateTime($time, new \DateTimeZone('America/Chicago'));
        return new UTCDateTime($php_date->getTimestamp() * 1000);
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
    {   //Get datarange
        $daterangeName = "1-Jul/14-Jul";
        $arrayOfDates = $this->getArrayOfDates($daterangeName);
        $daterangeArray = array();
        //Store code CEG
        $CEG = array();
        $CEG['General and Admin'] = $this->databasePrep($request->get('row0'), $arrayOfDates);
        $CEG['Staff Meetings and HR'] = $this->databasePrep($request->get('row1'), $arrayOfDates);
        $daterangeArray['CEG'] = $CEG;
        //$timesheet->CEG = $CEG;

        //Store Code CEGTRNG
        $CEGTRNG = array();
        $CEGTRNG['Research and Training'] = $this->databasePrep($request->get('row2'), $arrayOfDates);
        $daterangeArray['CEGTRNG'] = $CEGTRNG;
        //$timesheet->CEGTRNG = $CEGTRNG;

        //Store Code CEGEDU
        $CEGEDU = array();
        $CEGEDU['Formal EDU'] = $this->databasePrep($request->get('row3'), $arrayOfDates);
        $daterangeArray['CEGEDU'] = $CEGEDU;
        //$timesheet->CEGEDU = $CEGEDU;

        //Store Code CEGMKTG
        $CEGMKTG = array();
        $CEGMKTG['General Marketing'] = $this->databasePrep($request->get('row4'), $arrayOfDates);
        $daterangeArray['CEGMKTG'] = $CEGMKTG;
        //$timesheet->CEGMKTG = $CEGMKTG;

        $timesheet->$daterangeName = $daterangeArray;

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
                        $arr[$string] = $this->databasePrep($request->get('row'.$i), $arrayOfDates);
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
                        if($timesheet->$code != null){
                            $daterangeArray = array_merge($daterangeArray, $arr);   //might need to be fixed in future with edit
                        }
                        else{
                            $daterangeArray = $arr;
                        }
                    }
                }
            }
            if(count($arrayCodes) > 0){
                $Additional_Codes = $arrayCodes;
                array_push($daterangeArray, $Additional_Codes);
                $timesheet->$daterangeName = $daterangeArray;
            }
        }
        dd($timesheet);
        $timesheet->save();
    }


    public function check()
    {
        $date = getDate();
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
