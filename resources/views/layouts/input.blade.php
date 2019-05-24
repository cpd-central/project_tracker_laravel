<!doctype html>
<html>
  <head>
    @include('includes.navbar')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body>

    <div class="container">
      <h2><b>@yield('title')</b></h2>    
      <h4>@yield('h4proposal')</h4>
      <div class="container">
      </div>
      <form method="post">
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


            <input type="text" class="form-control" name="projectname" value="@yield('projectname')">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontactname" value="@yield('clientcontactname')">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="@yield('clientcompany')">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="mwsize" class="form-control" name="mwsize" value="@yield('mwsize')">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="text" class="form-control" name="voltage" value="@yield('voltage')">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="text" class="form-control" name="dollarvalueinhouse" value="@yield('dollarvalueinhouse')">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="text" class="form-control" name="dateproposed" value="@yield('dateproposed')">
          </div>
          <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="text" class="form-control" name="datentp" value="@yield('datentp')">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="text" class="form-control" name="dateenergization" value="@yield('dateenergization')">
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
              <input id="element_10_1" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Electrical Engineering" />Electrical Engineering
            </label>
            <label class="checkbox-inline" for="civilengineering">
              <input id="element_10_2" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Civil Engineering" />Civil Engineering
            </label>
            <label class="checkbox-inline" for="structuralmechanicalengineering">
              <input id="element_10_3" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Structural/Mechanical Engineering" />Structural/Mechanical Engineering
            </label>
            <label class="checkbox-inline" for="procurement">
              <input id="element_10_4" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Procurement" />Procurement
            </label>
            <label class="checkbox-inline" for="construction">
              <input id="element_10_5" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Construction" />Construction
            </label>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <br>	
            <h4>@yield('h4won')</h4>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectwon">Project Proposed/Won/Expired:</label>
            <select class="form-control" id="sel1" name="projectstatus" value="@yield('projectwon')">
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
        <div class="row">
          <div class="form-group col-md-4">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
























