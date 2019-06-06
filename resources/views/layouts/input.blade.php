<!doctype html>
<html>
  <head>
    @include('includes.navbar')
  </head>
  <body>

    @if(Session::has('message'))
    <div class="alert alert-danger">
        {{ Session::get('message') }}
    </div>
    @endif

    <div class="container">
      <h2><b>@yield('title')</b></h2>    
      <h4>@yield('h4proposal')</h4>
      <div class="container">
      </div>
      <form method="post">
        @csrf

        @if(Session::has('message'))
        <div class="row">
          <!--<div class="col-md-4"></div>-->
          <div class="form-group col-md-4">
            <label for="cegproposalauther">CEG Proposal Author</label>
            <input type="text" class="form-control" name="cegproposalauthor" value="<?= Session::get('cegproposalauthor')?>" required>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectname">Project Name:</label>

        <input type="text" class="form-control" name="projectname" value="<?= Session::get('projectname')?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontactname" value="<?= Session::get('clientcontactname')?>" required>
          </div>
          

          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="<?= Session::get('clientcompany')?>">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="number" class="form-control" name="mwsize" value="<?= Session::get('mwsize')?>">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="number" class="form-control" name="voltage" value="<?= Session::get('voltage')?>">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="number" class="form-control" name="dollarvalueinhouse" value="<?= Session::get('dollarvalueinhouse')?>">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="date" class="form-control" name="dateproposed" value="<?= Session::get('dateproposed')?>">
          </div>
            <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="date" class="form-control" name="datentp" value="<?= Session::get('datentp')?>">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="date" class="form-control" name="dateenergization" value="<?= Session::get('dateenergization')?>">
          </div>
        </div>


        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">Project Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="projecttypewind">
              <input id="element_9_1" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Wind" <?= Session::get('Wind')?>/>Wind
            </label>
            <label class="checkbox-inline" for="projecttypesolar">
              <input id="element_9_2" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Solar" <?= Session::get('Solar')?>/>Solar
            </label>
            <label class="checkbox-inline" for="projecttypestorage">
              <input id="element_9_3" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Storage" <?= Session::get('Storage')?>/>Storage
            </label>
            <label class="checkbox-inline" for="projecttypearray">
              <input id="element_9_4" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Array" <?= Session::get('Array')?>/>Array
            </label>
            <label class="checkbox-inline" for="projecttypetransmission">
              <input id="element_9_5" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Transmission" <?= Session::get('Transmission')?>/>Transmission
            </label>
            <label class="checkbox-inline" for="projecttypesubstation">
              <input id="element_9_6" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Substation" <?= Session::get('Substation')?>/>Substation
            </label>
            <label class="checkbox-inline" for="projecttypedistribution">
              <input id="element_9_7" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Distribution" <?= Session::get('Distribution')?>/>Distribution
            </label>
            <label class="checkbox-inline" for="projecttypescada">
              <input id="element_9_8" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="SCADA" <?= Session::get('SCADA')?>/>SCADA
            </label>
            <label class="checkbox-inline" for="projecttypestudy">
              <input id="element_9_9" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Study" <?= Session::get('Study')?>/>Study
            </label>

          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">EPC Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="electricalengineering">
              <input id="element_10_1" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Electrical Engineering" <?= Session::get('Electrical Engineering')?>/>Electrical Engineering
            </label>
            <label class="checkbox-inline" for="civilengineering">
              <input id="element_10_2" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Civil Engineering" <?= Session::get('Civil Engineering')?>/>Civil Engineering
            </label>
            <label class="checkbox-inline" for="structuralmechanicalengineering">
              <input id="element_10_3" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Structural/Mechanical Engineering" <?= Session::get('Structural/Mechanical Engineering')?>/>Structural/Mechanical Engineering
            </label>
            <label class="checkbox-inline" for="procurement">
              <input id="element_10_4" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Procurement" <?= Session::get('Procurement')?>/>Procurement
            </label>
            <label class="checkbox-inline" for="construction">
              <input id="element_10_5" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Construction" <?= Session::get('Construction')?>/>Construction
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
              <option selected="selected"><?= Session::get('projectstatus')?></option>  <!-- Fix -->
              @yield('projectstatus')
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="projectcode">Project Code:</label>
            <input type="text" class="form-control" name="projectcode" value="<?= Session::get('projectcode')?>">
          </div>
          <div class="form-group col-md-4">
            <label for="projectmanager">CEG Project Manager:</label>
            <input type="text" class="form-control" name="projectmanager" value="<?= Session::get('projectmanager')?>">
          </div>
        </div>



        @else
        <div class="row">
          <!--<div class="col-md-4"></div>-->
          <div class="form-group col-md-4">
            <label for="cegproposalauther">CEG Proposal Author</label>
            <input type="text" class="form-control" name="cegproposalauthor" value="@yield('cegproposalauthor')" required>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="projectname">Project Name:</label>


            <input type="text" class="form-control" name="projectname" value="@yield('projectname')" required>
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontactname" value="@yield('clientcontactname')" required>
          </div>
          

          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="@yield('clientcompany')">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="number" class="form-control" name="mwsize" value="@yield('mwsize')">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="number" class="form-control" name="voltage" value="@yield('voltage')">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="number" class="form-control" name="dollarvalueinhouse" value="@yield('dollarvalueinhouse')">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="date" class="form-control" name="dateproposed" value="@yield('dateproposed')">
          </div>
            <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="date" class="form-control" name="datentp" value="@yield('datentp')">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="date" class="form-control" name="dateenergization" value="@yield('dateenergization')">
          </div>
        </div>


        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">Project Type:</label>
          </div>

          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="projecttypewind">
              <input id="element_9_1" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Wind" @yield('projecttypewind')/>Wind
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
        @endif
        <div class="row">
          <div class="form-group col-md-4">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
     
      <!-- we may use these later, but for now we'll just use required client side -->
      <!-- alerts -->
      <!--@if (count($errors)) 
      <div class="form-group"> 
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li> 
            @endforeach
          </ul>
        </div>
      </div>
      @endif-->

    </div>
  </body>
</html>
























