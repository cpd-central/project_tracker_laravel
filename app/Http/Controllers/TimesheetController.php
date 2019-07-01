<?php

namespace App\Http\Controllers;

use App\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    protected function databasePrep($array){
        if($array){
            foreach($array as $day){
              if($day == null || $day ==""){
                $day = 0;
              }
            }
          array_walk($array, function(&$x){$x = (float)($x);});
        }
        return $array;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.timesheet');
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
    {   //Store code CEG
        $CEG = array();
        $CEG['General and Admin'] = $this->databasePrep($request->get('row0'));
        $CEG['Staff Meetings and HR'] = $this->databasePrep($request->get('row1'));
        $timesheet->CEG = $CEG;
        //Store Code CEGTRNG
        $CEGTRNG = array();
        $CEGTRNG['Research and Training'] = $this->databasePrep($request->get('row2'));
        $timesheet->CEGTRNG = $CEGTRNG;
        //Store Code CEGEDU
        $CEGEDU = array();
        $CEGEDU['Formal EDU'] = $this->databasePrep($request->get('row3'));
        $timesheet->CEGEDU = $CEGEDU;
        //Store Code CEGMKTG
        $CEGMKTG = array();
        $CEGMKTG['General Marketing'] = $this->databasePrep($request->get('row4'));
        $timesheet->CEGMKTG = $CEGMKTG;
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
                        $arr[$string] = $this->databasePrep($request->get('row'.$i));
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
                            $timesheet->$code = array_merge($timesheet->$code, $arr);   //might need to be fixed in future with edit
                        }
                        else{
                            $timesheet->$code = $arr;
                        }
                    }
                }
            }
            if(count($arrayCodes) > 0){
                $timesheet->Additional_Codes = $arrayCodes;
            }
        }
        $timesheet->save();
    }


    public function check()
    {
        $collection = Timesheet::where('user', auth()->user()->email)->get();
        if(!$collection->isEmpty()){
            $timesheet = $collection[0];
            return $this->edit($timesheet);
        }
        else{
            return $this->index();
        }
    }


    public function edit($timesheet)
    {
        return view('pages.timesheet', compact('timesheet'));
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
