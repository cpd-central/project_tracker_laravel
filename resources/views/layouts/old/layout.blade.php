<!-- layout.blade.php -->

<!DOCTYPE html>
@extends('layouts/app')

@section('project_input_template')
<html>
  <head>
    <meta charset="utf-8">
    <title>@yield('title','Title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">  
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
    <style>
#checkbox {
  vertical-align:middle;
}

    </style>

  </head>


  <body>
    <div class="container">
      <h4>@yield('h4proposal','Title')</h4>
      <div class="container">
      </div>
      <form method="post" action="{{url('newproject')}}">
        @csrf
        <div class="row">
          <!--<div class="col-md-4"></div>-->
          <div class="form-group col-md-4">
            <label for="cegproposalauther">CEG Proposal Author</label>

            <input type="text" class="form-control" name="cegproposalauthor" value="@yield('cegproposalauthor')">
          </div>			  
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectname">Project Name:</label>

            @yield('projectname')
            <input type="text" class="form-control" name="projectname" value="@yield('projectname')">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontractname" value="@yield('clientcontractname')">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="mwsize" class="form-control" name="mwsize" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="text" class="form-control" name="voltage" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="text" class="form-control" name="dollarvalueinhouse" value="">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="text" class="form-control" name="dateproposed" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="text" class="form-control" name="datentp" value="">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="text" class="form-control" name="dateenergization" value="">
          </div>
        </div>


        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">Project Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="projecttypewind">
              <input id="element_9_1" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Wind" />Wind
            </label>
            <label class="checkbox-inline" for="projecttypesolar">
              <input id="element_9_2" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Solar" />Solar
            </label>
            <label class="checkbox-inline" for="projecttypestorage">
              <input id="element_9_3" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Storage" />Storage
            </label>
            <label class="checkbox-inline" for="projecttypearray">
              <input id="element_9_4" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Array" />Array
            </label>
            <label class="checkbox-inline" for="projecttypetransmission">
              <input id="element_9_5" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Transmission" />Transmission
            </label>
            <label class="checkbox-inline" for="projecttypesubstation">
              <input id="element_9_6" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Substation" />Substation
            </label>
            <label class="checkbox-inline" for="projecttypedistribution">
              <input id="element_9_7" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Distribution" />Distribution
            </label>
            <label class="checkbox-inline" for="projecttypescada">
              <input id="element_9_8" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="SCADA" />SCADA
            </label>
            <label class="checkbox-inline" for="projecttypestudy">
              <input id="element_9_9" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Study" />Study
            </label>

          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">EPC Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="electricalengineering">
              <input id="element_10_1" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="@yield('electricalengineering')" />Electrical Engineering
            </label>
            <label class="checkbox-inline" for="civilengineering">
              <input id="element_10_2" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="@yield('civilengineering')" />Civil Engineering
            </label>
            <label class="checkbox-inline" for="structuralmechanicalengineering">
              <input id="element_10_3" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="@yield('structuralmechanicalengineering')" />Structural/Mechanical Engineering
            </label>
            <label class="checkbox-inline" for="procurement">
              <input id="element_10_4" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="@yield('procurement')" />Procurement
            </label>
            <label class="checkbox-inline" for="construction">
              <input id="element_10_5" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="@yield('construction')" />Construction
            </label>
          </div>
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <br>	
            <h4>@yield('h4won','Title')</h4>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectwon">Project Proposed/Won/Expired:</label>
            <select class="form-control" id="sel1" value="@yield('sel1')">
              <option>Proposed</option>
              <option>Won</option>
              <option>Expired</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="projectcode">Project Code:</label>
            <input type="text" class="form-control" name="projectcode" value="@yield('projectcode')">
          </div>
          <div class="form-group col-md-4">
            <label for="projectmanager">CEG Project Manager:</label>
            <input type="text" class="form-control" name="projectmanager" value="@yield('projectmanager')">
          </div>
        </div>

        <!--<div class="row">
          <div class="form-group col-md-4">
          <label for="fill">fill:</label>
          <input type="text" class="form-control" name="fill">
          </div>
          <div class="form-group col-md-4">
          <label for="fill">fill:</label>
          <input type="text" class="form-control" name="fill">
          </div>
          <div class="form-group col-md-4">
          <label for="fill">fill:</label>
          <input type="text" class="form-control" name="fill">
          </div>
          </div>-->




          <div class="row">
            <div class="form-group col-md-4">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>
      </form>
    </div>
  </body>
</html>



