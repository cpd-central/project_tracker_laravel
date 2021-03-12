<?php
  /**
   * Checks if array $typeArray is not null and then checks to see if $type is in $typeArray.
   * @param $type - variable to be checked if its in $typeArray. 
   * @param $typeArray - array that contains keywords of boxes that are checked.
   * @return "checked"
   */
  function check_project_box2($type, $typeArray) {
    if($typeArray == null || $typeArray == "") {
      return false;
    }else
    {if(in_array($type, $typeArray)) {
        return true;
      }
      else{
        return false;
      }
    }
  }

  /**
   * Checks if $saved_bill is not null and then checks to see if $type is equal to $saved_bill.
   * @param $type - billing type string to be checked if its equal to $saved_bill 
   * @param $saved_bill - string that contains keyword of radio button to be checked.
   * @return boolean
   */
  function check_billing_method($type, $saved_bill) {
    if($saved_bill == null || $saved_bill == "") {
      return false;
    }else
    {if($type == $saved_bill) {
        return true;
      }
      else{
        return false;
      }
    }
  }

  /**
   * Returns the array of $project['monthlypercent']
   * @return $project['monthlypercent']
   */
  function get_array(){
    return $project['monthlypercent'];
  }

  /**
   * Returns the integer representation of the month difference between two dates.
   * @param $dateFrom
   * @param $dateTo
   * @return an Integer
   */
  function monthDiff($dateFrom, $dateTo) {
        return $dateTo.getMonth() - $dateFrom.getMonth() + 
        (12 * ($dateTo.getFullYear() - $dateFrom.getFullYear())) + 1;
      }
?>
<!doctype html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<style>
  input[type="radio"]{  
  margin: 0 2px 0 15px;
}
</style>
<html>
  <title id="page-title">@yield('page-title')</title>
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
      <?php if(!empty($success)){ ?>
        <div class="alert alert-success">
          <p>{{ $success }}</p>
        </div>
        <br>
      <?php }?>
      <h2><b>@yield('title')</b></h2>    
      <h4>@yield('h4proposal')</h4>
      @if(Str::contains(url()->current(), 'editproject'))
      <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}" class="btn btn-warning">Manage Due Dates</a></td>
      @endif
      <div class="container">
      </div>
      <form method="post">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="cegproposalauthor">CEG Proposal Author:</label>
            <select class="form-control" id="sel2" name="cegproposalauthor">  
            @if(old('cegproposalauthor'))   
              @foreach($authors as $author)
                <?php if (old('cegproposalauthor') == $author->name){?>
                  <option value="<?=$author->name?>" selected="selected"><?=$author->name?></option>
                <?php } else { ?>
                  <option value="<?=$author->name?>"><?=$author->name?></option>
                <?php } ?>
              @endforeach
            @else
              @yield('cegproposalauthor')
            @endif
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="projectname">Project Name:</label>
            <input type="text" class="form-control" name="projectname" value="@if(old('projectname')){{ old('projectname') }}@else<?= $__env->yieldContent('projectname')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="clientcontactname">Client Contact Name:</label>
            <input type="text" class="form-control" name="clientcontactname" value="@if(old('clientcontactname')){{ old('clientcontactname') }}@else<?= $__env->yieldContent('clientcontactname')?>@endif">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="state">State:</label>
            <input type="text" class="form-control" name="state" value="@if(old('state')){{ old('state') }}@else<?= $__env->yieldContent('state')?>@endif">
          </div>	
        <div class="form-group col-md-4">
          <label for="utility">Utility:</label>
          <input type="text" class="form-control" name="utility" value="@if(old('utility')){{ old('utility') }}@else<?= $__env->yieldContent('utility')?>@endif">
        </div>	
          <div class="form-group col-md-4">
            <label for="clientcompany">Client Company:</label>
            <input type="text" class="form-control" name="clientcompany" value="@if(old('clientcompany')){{ old('clientcompany') }}@else<?= $__env->yieldContent('clientcompany')?>@endif">
          </div>	
        </div>


        <div class="row">
          <div class="form-group col-md-4">
            <label for="mwsize">MW Size:</label>
            <input type="number" class="form-control" step=0.001 name="mwsize" value="@if(old('mwsize')){{ old('mwsize') }}@else<?= $__env->yieldContent('mwsize')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="voltage">Voltage:</label>
            <input type="number" class="form-control" step=0.001 name="voltage" value="@if(old('voltage')){{ old('voltage') }}@else<?= $__env->yieldContent('voltage')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="dollarvalueinhouse">Dollar Value (in-house expense):</label>
            <input type="number" class="form-control" name="dollarvalueinhouse" value="@if(old('dollarvalueinhouse')){{ old('dollarvalueinhouse') }}@else<?= $__env->yieldContent('dollarvalueinhouse')?>@endif">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="dateproposed">Date of Proposed:</label>
            <input type="date" class="form-control" name="dateproposed" value="@if(old('dateproposed'))<?= old('dateproposed') ?>@else<?= $__env->yieldContent('dateproposed')?>@endif">
          </div>
            <div class="form-group col-md-4">
            <label for="datentp">Date of Notice To Proceed:</label>
            <input type="date" class="form-control" id="datentp" name="datentp" value="@if(old('datentp'))<?= old('datentp') ?>@else<?= $__env->yieldContent('datentp')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="dateenergization">Date of Energization:</label>
            <input type="date" class="form-control" id="dateenergization" name="dateenergization" value="@if(old('dateenergization'))<?= old('dateenergization') ?>@else<?= $__env->yieldContent('dateenergization')?>@endif">
          <input type="checkbox" id="dateenergizationunknown" name="dateenergizationunknown" @if(old('dateenergizationunknown') == "on"){{"checked"}}@else @if(isset($project['dateenergization']) && $project['dateenergization'] == "Unknown"){{"checked"}} @endif @endif><small>Date of Energization Unknown</small>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12">
            <table class="table table-hover" id="dynamic_field">
              <tbody>
                    @if(isset($project['monthlypercent']))
                    <?php $month = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                      

                      $mm = ((int) date('n', strtotime($project['datentp'])) - 1);
                      $year = (int) date('Y', strtotime($project['datentp']));

                      $row = (int) (count($project['monthlypercent']) / 4);   //4 text boxes per row
                      $mod = count($project['monthlypercent']) % 4;
                      $i = 0;

                      //caption header  ?>
                       <tr>
                        <td colspan="100%">
                          <h5 class="text-center">Input percents as decimals below. For reference: 0.05 = 5%, 0.7 = 70%, 1 = 100%</h5>
                        </td>
                        </tr>

                      <?php
                      while($i < count($project['monthlypercent'])) {
                        for($k = 1; $k <= $row; $k++) { ?>
                          <tr>
                      <?php for($j = 0; $j < 4; $j++) {
                            if($mm > 11){
                              $mm = 0;
                              $year = $year + 1;
                            }?>
                          <td>{{$month[$mm].$year}}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" min="0.00" max="1.00" id="{{'month'.$mm.'year'.$year}}" name="monthly_percent[]" value="{{$project['monthlypercent'][$i]}}" />
                                        </td>
                                    <?php $mm++;
                                          $i++;
                          } ?>
                          </tr>         
                      <?php }
                        if($mod > 0) { ?>
                          <tr>
                      <?php for($w = 0; $w < $mod; $w++) {
                            if($mm > 11){
                              $mm = 0;
                              $year = $year + 1;
                            } ?>
                            <td>{{$month[$mm].$year}}</td>
                                        <td>
                                            <input type="number" step="0.01" min="0.00" max="1.00" class="form-control" id="{{'month'.$mm.'year'.$year}}" name="monthly_percent[]" value="{{$project['monthlypercent'][$i]}}" />
                                        </td>
                                     <?php $mm++;
                                           $i++; 
                                      }?>
                          </tr>   
                        <?php  
                      }    
                      }
                      ?>
                    @else
                    @endif
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">Project Type:</label>
          </div>

          <?php $projectTypeArray = old('projecttype_checklist')?>
          <div class="form-group col-md-10">
            <label margin:0 auto; width:80%; class="checkbox-inline" for="projecttypewind">
              <input id="element_9_1" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Wind" @if(check_project_box2('Wind', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypewind')?>@endif/>Wind
            </label>
            <label class="checkbox-inline" for="projecttypesolar">
              <input id="element_9_2" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Solar" @if(check_project_box2('Solar', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypesolar')?>@endif/>Solar
            </label>
            <label class="checkbox-inline" for="projecttypestorage">
              <input id="element_9_3" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Storage" @if(check_project_box2('Storage', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypestorage')?>@endif/>Storage
            </label>
            <label class="checkbox-inline" for="projecttypearray">
              <input id="element_9_4" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Array" @if(check_project_box2('Array', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypearray')?>@endif/>Array
            </label>
            <label class="checkbox-inline" for="projecttypetransmission">
              <input id="element_9_5" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Transmission" @if(check_project_box2('Transmission', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypetransmission')?>@endif/>Transmission
            </label>
            <label class="checkbox-inline" for="projecttypesubstation">
              <input id="element_9_6" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Substation" @if(check_project_box2('Substation', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypesubstation')?>@endif/>Substation
            </label>
            <label class="checkbox-inline" for="projecttypedistribution">
              <input id="element_9_7" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Distribution" @if(check_project_box2('Distribution', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypedistribution')?>@endif/>Distribution
            </label>
            <label class="checkbox-inline" for="projecttypescada">
              <input id="element_9_8" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="SCADA" @if(check_project_box2('SCADA', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypescada')?>@endif/>SCADA
            </label>
            <label class="checkbox-inline" for="projecttypestudy">
              <input id="element_9_9" name="projecttype_checklist[]" class="element checkbox" type="checkbox" value="Study" @if(check_project_box2('Study', $projectTypeArray)){{"checked"}}@else<?= $__env->yieldContent('projecttypestudy')?>@endif/>Study
            </label>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-2">
            <label for="fill">EPC Type:</label>
          </div>

          <?php $epcTypeArray = old('epctype_checklist')?>
          <div class="form-group col-md-10">
            <label class="checkbox-inline" for="electricalengineering">
              <input id="element_10_1" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Electrical Engineering" @if(check_project_box2('Electrical Engineering', $epcTypeArray)){{"checked"}}@else<?= $__env->yieldContent('epctypeelectricalengineering')?>@endif/>Electrical Engineering
            </label>
            <label class="checkbox-inline" for="civilengineering">
              <input id="element_10_2" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Civil Engineering" @if(check_project_box2('Civil Engineering', $epcTypeArray)){{"checked"}}@else<?= $__env->yieldContent('epctypecivilengineering')?>@endif/>Civil Engineering
            </label>
            <label class="checkbox-inline" for="structuralmechanicalengineering">
              <input id="element_10_3" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Structural/Mechanical Engineering" @if(check_project_box2('Structural/Mechanical Engineering', $epcTypeArray)){{"checked"}}@else<?= $__env->yieldContent('epctypestructuralmechanicalengineering')?>@endif/>Structural/Mechanical Engineering
            </label>
            <label class="checkbox-inline" for="procurement">
              <input id="element_10_4" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Procurement" @if(check_project_box2('Procurement', $epcTypeArray)){{"checked"}}@else<?= $__env->yieldContent('epctypeprocurement')?>@endif/>Procurement
            </label>
            <label class="checkbox-inline" for="construction">
              <input id="element_10_5" name="epctype_checklist[]" class="element checkbox" type="checkbox" value="Construction" @if(check_project_box2('Construction', $epcTypeArray)){{"checked"}}@else<?= $__env->yieldContent('epctypeconstruction')?>@endif/>Construction
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
              @if(old('projectstatus'))
                @if (old('projectstatus') == 'Won') 
                  <option>Proposed</option>
                  <option selected="selected">Won</option>
                  <option>Probable</option>
                  <option>Expired</option>
                  <option>Done and Billing Complete</option>
                @elseif (old('projectstatus') == 'Expired')
                  <option>Proposed</option>
                  <option>Won</option>
                  <option>Probable</option>
                  <option selected="selected">Expired</option>
                  <option>Done and Billing Complete</option>
                @elseif (old('projectstatus') == 'Probable')
                  <option>Proposed</option>
                  <option>Won</option>
                  <option selected="selected">Probable</option>
                  <option>Expired</option>
                  <option>Done and Billing Complete</option>
                @elseif (old('projectstatus') == 'Done and Billing Complete')
                  <option>Proposed</option>
                  <option>Won</option>
                  <option>Probable</option>
                  <option>Expired</option>
                  <option selected="selected">Done and Billing Complete</option>
                @else
                  <option selected="selected">Proposed</option>
                  <option>Won</option>
                  <option>Probable</option>
                  <option>Expired</option>
                  <option>Done and Billing Complete</option>
                @endif
              @else
                @yield('projectstatus')
              @endif
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="projectcode">Project Code:</label>
            <input type="text" class="form-control" name="projectcode" value="@if(old('projectcode'))<?= old('projectcode') ?>@else<?= $__env->yieldContent('projectcode')?>@endif">
          </div>

          <div class="form-group col-md-4">
            <label for="projectmanager">CEG Project Manager:</label>
            <select class="form-control" id="sel2" name="projectmanager">  
            @if(old('projectmanager'))   
              @foreach($pms as $pm)
                <?php if (old('projectmanager') == $pm->name){?>
                  <option value="<?=$pm->name?>" selected="selected"><?=$pm->name?></option>
                <?php } else { ?>
                  <option value="<?=$pm->name?>"><?=$pm->name?></option>
                <?php } ?>
              @endforeach
            @else
              @yield('projectmanager')
            @endif
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="projectnotes">Project Notes:</label>
          <textarea class="form-control" name="projectnotes" id="projectnotes" rows="3">@if(old('projectnotes'))<?= old('projectnotes') ?>@else<?= $__env->yieldContent('projectnotes')?>@endif</textarea>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="billingcontact">Billing Contact</label>
          <input type="text" class="form-control" name="billingcontact" value="@if(old('billingcontact')){{ old('billingcontact') }}@else<?= $__env->yieldContent('billingcontact')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="billingcontactemail">Billing Contact Email:</label>
            <input type="text" class="form-control" name="billingcontactemail" value="@if(old('billingcontactemail')){{ old('billingcontactemail') }}@else<?= $__env->yieldContent('billingcontactemail')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="billingmethod">Billing Method:</label><br>
            @if(isset($project))
              <label class="billingmethod" for="TandM">
                <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="TandM" @if(check_billing_method('TandM', $project['billing_method'])){{"checked"}}@else<?= $__env->yieldContent('TandM')?>@endif/>T&M
              </label>
              <label class="billingmethod" for="Lump">
              <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="Lump" @if(check_billing_method('Lump', $project['billing_method'])){{"checked"}}@else<?= $__env->yieldContent('Lump')?>@endif/>Lump
              </label>
              <label class="billingmethod" for="SOV">
                <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="SOV" @if(check_billing_method('SOV', $project['billing_method'])){{"checked"}}@else<?= $__env->yieldContent('SOV')?>@endif/>SOV
              </label>
            @else
              <label class="billingmethod" for="TandM">
                <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="TandM" <?= $__env->yieldContent('TandM')?>/>T&M
              </label>
              <label class="billingmethod" for="Lump">
              <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="Lump" <?= $__env->yieldContent('Lump')?>/>Lump
              </label>
              <label class="billingmethod" for="SOV">
                <input id="element_10_1" name="billingmethod" class="element checkbox" type="radio" value="SOV" <?= $__env->yieldContent('SOV')?>/>SOV
              </label>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-12">
            <label for="billingnotes">Billing Notes:</label>
            <input type="text" class="form-control" name="billingnotes" value="@if(old('billingnotes')){{ old('billingnotes') }}@else<?= $__env->yieldContent('billingnotes')?>@endif">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-12">
            <label for="filelocationofproposal">Location of Proposal:</label>
            <input type="text" class="form-control" name="filelocationofproposal" value="@if(old('filelocationofproposal')){{ old('filelocationofproposal') }}@else<?= $__env->yieldContent('filelocationofproposal')?>@endif">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-12">
            <label for="filelocationofproject">Location of Project:</label>
            <input type="text" class="form-control" name="filelocationofproject" value="@if(old('filelocationofproject')){{ old('filelocationofproject') }}@else<?= $__env->yieldContent('filelocationofproject')?>@endif">
          </div>
        </div>
      </form>
    </div>
    <script type="text/javascript" src="{{ URL::asset('js/monthlypercent.js')}}"></script>
  </body>
</html>
