<!doctype html>
<html>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      </br>
      </br> 
      @if (count($errors)) 
      <div class="form-group"> 
        <div class="alert alert-danger">
          <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li> 
          @endforeach
          </ul>
        </div>
      </div>
      @endif
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
          <input type="text" class="form-control" name="cegproposalauthor" value="@if(old('cegproposalauthor')){{ old('cegproposalauthor') }} @else @yield('cegproposalauthor') @endif">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectname">Project Name:</label>


            <input type="text" class="form-control" name="projectname" value="@if(old('projectname')){{ old('projectname') }} @else @yield('projectname') @endif">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontactname" value="@if(old('clientcontactname')){{ old('clientcontactname') }} @else @yield('clientcontactname') @endif">
          </div>
          

          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="@if(old('clientcompany')){{ old('clientcompany') }} @else @yield('clientcompany') @endif">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="number" class="form-control" name="mwsize" value="@if((int) old('mwsize') != 0){{ old('mwsize') }}@else @yield('mwsize') @endif">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="number" class="form-control" name="voltage" value="@if((int) old('voltage') != 0){{ old('voltage') }}@else @yield('voltage') @endif">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="number" class="form-control" name="dollarvalueinhouse" value="@if((int) old('dollarvalueinhouse') != 0){{ old('dollarvalueinhouse') }}@else @yield('dollarvalueinhouse') @endif">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="date" class="form-control" name="dateproposed" value="@if(old('dateproposed')){{ old('dateproposed') }} @else @yield('dateproposed') @endif">
          </div>
            <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="date" class="form-control" name="datentp" value="@if(old('datentp')){{ old('datentp') }} @else @yield('datentp') @endif">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="date" class="form-control" name="dateenergization" value="@if(old('dateenergization')){{ old('dateenergization') }} @else @yield('dateenergization') @endif">
          </div>
        </div>


        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">Project Type:</label>
          </div>
        
          <div class="form-group col-md-10">
            <label margin:0 auto; width:80%; class="checkbox-inline" for="projecttypewind">
              <input id="element_9_1" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Wind" @if(old('Wind')){{ old('Wind') }} @else @yield('Wind') @endif/>Wind
            </label>
            <label class="checkbox-inline" for="projecttypesolar">
              <input id="element_9_2" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Solar" @yield('projecttypesolar')/>Solar
            </label>
            <label class="checkbox-inline" for="projecttypestorage">
              <input id="element_9_3" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Storage" @yield('projecttypestorage')/>Storage
            </label>
            <label class="checkbox-inline" for="projecttypearray">
              <input id="element_9_4" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Array" @yield('projecttypearray')/>Array
            </label>
            <label class="checkbox-inline" for="projecttypetransmission">
              <input id="element_9_5" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Transmission" @yield('projecttypetransmission')/>Transmission
            </label>
            <label class="checkbox-inline" for="projecttypesubstation">
              <input id="element_9_6" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Substation" @yield('projecttypesubstation')/>Substation
            </label>
            <label class="checkbox-inline" for="projecttypedistribution">
              <input id="element_9_7" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Distribution" @yield('projecttypedistribution')/>Distribution
            </label>
            <label class="checkbox-inline" for="projecttypescada">
              <input id="element_9_8" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="SCADA" @yield('projecttypescada')/>SCADA
            </label>
            <label class="checkbox-inline" for="projecttypestudy">
              <input id="element_9_9" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Study" @yield('projecttypestudy')/>Study
            </label>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">EPC Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="electricalengineering">
              <input id="element_10_1" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Electrical Engineering" @yield('epctypeelectricalengineering')/>Electrical Engineering
            </label>
            <label class="checkbox-inline" for="civilengineering">
              <input id="element_10_2" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Civil Engineering" @yield('epctypecivilengineering')/>Civil Engineering
            </label>
            <label class="checkbox-inline" for="structuralmechanicalengineering">
              <input id="element_10_3" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Structural/Mechanical Engineering" @yield('epctypestructuralmechanicalengineering')/>Structural/Mechanical Engineering
            </label>
            <label class="checkbox-inline" for="procurement">
              <input id="element_10_4" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Procurement" @yield('epctypeprocurement')/>Procurement
            </label>
            <label class="checkbox-inline" for="construction">
              <input id="element_10_5" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Construction" @yield('epctypeconstruction')/>Construction
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
            <label for="projectstatus">Project Proposed/Won/Expired:</label>
            <select class="form-control" id="sel1" name="projectstatus"> 
                @yield('projectstatus')
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
























