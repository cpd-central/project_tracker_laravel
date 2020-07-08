<!doctype html>

<!-- This page creates the entire field layout of the manage_project page, as well as the javascrpit for all the buttons -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<html>
    <title id="page-title">@yield('page-title')</title>
    <head>
      @include('includes.navbar')
    </head>
    <body>
      <form method="post">
        @csrf
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
          <!-- Title that displays the project name, project manager and date of energization for the project that is being managed -->
          <div class="row">
            <div class="form-group col-md-4">
                <label for="projectname">Project Name:</label>
                <input type="text" class="form-control" name="projectname" readonly value="@if(old('projectname')){{ old('projectname') }} @else<?= $__env->yieldContent('projectname')?>@endif">
              </div>
              <div class="form-group col-md-4">
                <label for="projectmanager">CEG Project Manager:</label>
                <input type="text" class="form-control" name="projectmanager" readonly value="@if(old('projectmanager'))<?= old('projectmanager') ?>@else<?= $__env->yieldContent('projectmanager')?>@endif">
              </div>
              <div class="form-group col-md-4">
                <label for="dateenergization">Date of Energization:</label>
                <input type="date" class="form-control" id="dateenergization" name="dateenergization" readonly value="@if(old('dateenergization'))<?= old('dateenergization') ?>@else<?= $__env->yieldContent('dateenergization')?>@endif">
              </div>
         </div>
        <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit Details</a></td>
        </br>
        </br>

      <!-- Physical Drawing Package -->

      @if(!is_null($physicalfields) || !isset($project['duedates']))
      <!-- Heading for Physical Drawing Package, displays the title and second-level fields -->
        <div id="physicalheading">
          <h5><b> Physical Drawing Package</b></h5>
          <div class="row">
            <div>
              <button type="button" class="btn btn-danger btn_remove" id="removephysical">Remove All Physical Fields</button>
              <button style="margin:10px;" type="button" class="btn btn-warning" id="addphysical">Add New Physical Field</button> 
            </div>
          </br>
          </div>
          <div class="row">
              <div class="form-group col-md-4">
                  <label for="physicalperson1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="physicalperson1" value="@if(old('physicalperson1')){{ old('physicalperson1') }} @else<?= $__env->yieldContent('physicalperson1')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="physicalperson2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="physicalperson2" name="physicalperson2" value="@if(old('physicalperson2'))<?= old('physicalperson2') ?>@else<?= $__env->yieldContent('physicalperson2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="physicaldue">Due Date</label>
                  <input type="date" class="form-control" id="physicaldue" name="physicaldue" value="@if(old('physicaldue'))<?= old('physicaldue') ?>@else<?= $__env->yieldContent('physicaldue')?>@endif">
              </div>
            </br>
          </div>
        </div>
      @endif
      @if(!isset($project['duedates']))
        <!-- Body for Physical Drawing Package if the project has no due dates yet, displays third-level fields -->
        <div id="physicalbody">
          <?php $physicalfields = 1; ?>
          <h6 id="physical{{$physicalfields}}title" style="margin-left: 55px"><b>90</b></h6>
              <div id="physical{{$physicalfields}}row" style="margin-left: 40px" class="row">
                  <div class="form-group col-md-4">
                  <label for="physical{{$physicalfields}}person1">Engineer/Person 1</label>
                      <input type="text" class="form-control" name="physical{{$physicalfields}}person1" value="@if(old('physical{{$physicalfields}}person1'))<?= old('physical{{$physicalfields}}person1') ?>@else<?= $__env->yieldContent('physical{{$physicalfields}}person1')?>@endif">
                  </div>
                  <div class="form-group col-md-4">
                      <label for="physical{{$physicalfields}}person2">Drafter/Person 2</label>
                      <input type="text" class="form-control" id="physical{{$physicalfields}}person2" name="physical{{$physicalfields}}person2" value="@if(old('physical{{$physicalfields}}person2'))<?= old('physical{{$physicalfields}}person2') ?>@else<?= $__env->yieldContent('physical{{$physicalfields}}person2')?>@endif">
                  </div>
                  <div class="form-group col-md-4">
                      <label for="physical{{$physicalfields}}due">Due Date</label>
                      <input type="date" class="form-control" id="physical{{$physicalfields}}due" name="physical{{$physicalfields}}due" value="@if(old('physical{{$physicalfields}}due'))<?= old('physical{{$physicalfields}}due') ?>@else<?= $__env->yieldContent('physical{{$physicalfields}}due')?>@endif">
                  </div>
                  <input type="hidden" id="physical{{$physicalfields}}name" name="physical{{$physicalfields}}name" value="90" readonly />
                </br>
              </div>
            <div style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                <button type="button" class="btn btn-danger btn_remove" id="physical{{$physicalfields}}">Remove Physical Field</button>
                <?php $physicalfields++; ?>
              </div>
            </br>
            </div>
          <h6 id="physical{{$physicalfields}}title" style="margin-left: 55px"><b>IFC</b></h6>
            <div id="physical{{$physicalfields}}row" style="margin-left: 40px" class="row">
                <div class="form-group col-md-4">
                    <label for="physical{{$physicalfields}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="physical{{$physicalfields}}person1" value="@if(old('physical{{$physicalfields}}person1'))<?= old('physical{{$physicalfields}}person1') ?> @else<?= $__env->yieldContent('physical{{$physicalfields}}person1')?>@endif">
                </div>
                <div class="form-group col-md-4">
                <label for="physical{{$physicalfields}}person2">Drafter/Person 2</label>
                    <input type="text" class="form-control" id="physical{{$physicalfields}}person2" name="physical{{$physicalfields}}person2" value="@if(old('physical{{$physicalfields}}person2'))<?= old('physical{{$physicalfields}}person2') ?>@else<?= $__env->yieldContent('physical{{$physicalfields}}person2')?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="physical{{$physicalfields}}due">Due Date</label>
                <input type="date" class="form-control" id="physical{{$physicalfields}}due" name="physical{{$physicalfields}}due" value="@if(old('physical{{$physicalfields}}due'))<?= old('physical{{$physicalfields}}due') ?>@else<?= $__env->yieldContent('physical{{$physicalfields}}due')?>@endif">
                </div>
                <input type="hidden" id="physical{{$physicalfields}}name" name="physical{{$physicalfields}}name" value="IFC" readonly />
              </br>
            </div>
            <div style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                <button type="button" class="btn btn-danger btn_remove" id="physical{{$physicalfields}}">Remove Physical Field</button>
              </div>
            </br>
            </div>
            </br>
        </div>
      @elseif($physicalfields > 0)
      <!-- Body for Physical Drawing Package if the project has saved due dates, displays third-level fields -->
        <div id="physicalbody">
          <?php $keycounter = 3; ?>
          <?php for($i = 1; $i <= $physicalfields; $i++){?>
            <?php $keys = array_keys($project['duedates']['physical']);?>
            <h6 id="physical{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
            <div id="physical{{$i}}row" style="margin-left: 40px" class="row">
                <div class="form-group col-md-4">
                    <label for="physical{{$i}}person1">Engineer/Person 1</label>
                    <input type="text" class="form-control" id="physical{{$i}}person1" name="physical{{$i}}person1" value="@if(old('physical{{$i}}person1'))<?= old('physical{{$i}}person1') ?>@else<?= $project['duedates']['physical'][$keys[$keycounter]]['person1'] ?>@endif">
                </div>
                <div class="form-group col-md-4">
                  <label for="physical{{$i}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="physical{{$i}}person2" name="physical{{$i}}person2" value="@if(old('physical{{$i}}person2'))<?= old('physical{{$i}}person2') ?>@else<?= $project['duedates']['physical'][$keys[$keycounter]]['person2'] ?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="physical{{$i}}due">Due Date</label>
                    <input type="date" class="form-control" id="physical{{$i}}due" name="physical{{$i}}due" value="@if(old('physical{{$i}}due'))<?= old('physical{{$i}}due') ?>@else<?= $project['duedates']['physical'][$keys[$keycounter]]['due'] ?>@endif">
                </div>
                <input type="hidden" id="physical{{$i}}name" name="physical{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
                <?php $keycounter++; ?>
              </br>
            </div>
            <div style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                <button type="button" class="btn btn-danger btn_remove" id="physical{{$i}}">Remove Physical Field</button>
              </div>
            </br>
            </div>
          <?php } ?>
        </div>
      @endif
      <input type="hidden" id="physicalfields" name="physicalfields" value="{{$physicalfields}}" readonly />
      <div id = "addedphysical">
      </div>

  <!-- Wiring and Controls Drawing Package -->
  
  @if(!is_null($controlfields) || !isset($project['duedates']))
    <!-- Heading for Wiring and Controls Drawing Package, displays the title and second-level fields -->
    <div id="controlheading">
      <h5><b> Wiring and Controls Drawing Package</b></h5>
      <div class="row">
        <div>
          <button type="button" class="btn btn-danger btn_remove" id="removecontrol">Remove All Control Fields</button>
            <button style="margin:10px;" type="button" class="btn btn-warning" id="addcontrol">Add New Control Field</button> 
        </div>
      </br>
      </div>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="controlperson1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="controlperson1" value="@if(old('controlperson1')){{ old('controlperson1') }} @else<?= $__env->yieldContent('controlperson1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="controlperson2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="controlperson2" name="controlperson2" value="@if(old('controlperson2'))<?= old('controlperson2') ?>@else<?= $__env->yieldContent('controlperson2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="controldue">Due Date</label>
              <input type="date" class="form-control" id="controldue" name="controldue" value="@if(old('controldue'))<?= old('controldue') ?>@else<?= $__env->yieldContent('controldue')?>@endif">
          </div>
        </br>
      </div>
    </div>
  @endif
  @if(!isset($project['duedates']))
  <!-- Body for Wiring and Controls Drawing Package if the project has not saved any due dates, displays third-level fields -->
    <div id= "controlbody">
      <?php $controlfields = 1; ?>
      <h6 id="control{{$controlfields}}title" style="margin-left: 55px"><b>90</b></h6>
          <div id="control{{$controlfields}}row" style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
              <label for="control{{$controlfields}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="control{{$controlfields}}person1" value="@if(old('control{{$controlfields}}person1'))<?= old('control{{$controlfields}}person1') ?>@else<?= $__env->yieldContent('control{{$controlfields}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="control{{$controlfields}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="control{{$controlfields}}person2" name="control{{$controlfields}}person2" value="@if(old('control{{$controlfields}}person2'))<?= old('control{{$controlfields}}person2') ?>@else<?= $__env->yieldContent('control{{$controlfields}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="control{{$controlfields}}due">Due Date</label>
                  <input type="date" class="form-control" id="control{{$controlfields}}due" name="control{{$controlfields}}due" value="@if(old('control{{$i}}due'))<?= old('control{{$controlfields}}due') ?>@else<?= $__env->yieldContent('control{{$controlfields}}due')?>@endif">
              </div>
              <input type="hidden" id="control{{$controlfields}}name" name="control{{$controlfields}}name" value="90" readonly />
            </br>
          </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="control{{$controlfields}}">Remove Control Field</button>
            <?php $controlfields++; ?>
          </div>
        </br>
        </div>
      <h6 id="control{{$controlfields}}title" style="margin-left: 55px"><b>IFC</b></h6>
        <div id="control{{$controlfields}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="control{{$controlfields}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="control{{$controlfields}}person1" value="@if(old('control{{$controlfields}}person1'))<?= old('control{{$controlfields}}person1') ?> @else<?= $__env->yieldContent('control{{$controlfields}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
            <label for="control{{$controlfields}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="control{{$controlfields}}person2" name="control{{$controlfields}}person2" value="@if(old('control{{$controlfields}}person2'))<?= old('control{{$controlfields}}person2') ?>@else<?= $__env->yieldContent('control{{$controlfields}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="control{{$controlfields}}due">Due Date</label>
            <input type="date" class="form-control" id="control{{$controlfields}}due" name="control{{$controlfields}}due" value="@if(old('control{{$controlfields}}due'))<?= old('control{{$controlfields}}due') ?>@else<?= $__env->yieldContent('control{{$controlfields}}due')?>@endif">
            </div>
            <input type="hidden" id="control{{$controlfields}}name" name="control{{$controlfields}}name" value="IFC" readonly />
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="control{{$controlfields}}">Remove Control Field</button>
          </div>
        </div>
        </br>
    </div>
  @elseif($controlfields > 0)
    <!-- Body for Wiring and Controls Drawing Package if the project has saved due dates, displays third-level fields -->
    <div id="controlbody">
      <?php $keycounter = 3; ?>
      <?php for($i = 1; $i <= $controlfields; $i++){?>
        <?php $keys = array_keys($project['duedates']['control']);?>
        <h6 id="control{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
        <div id="control{{$i}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="control{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" id="control{{$i}}person1" name="control{{$i}}person1" value="@if(old('control{{$i}}person1'))<?= old('control{{$i}}person1') ?>@else<?= $project['duedates']['control'][$keys[$keycounter]]['person1'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
              <label for="control{{$i}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="control{{$i}}person2" name="control{{$i}}person2" value="@if(old('control{{$i}}person2'))<?= old('control{{$i}}person2') ?>@else<?= $project['duedates']['control'][$keys[$keycounter]]['person2'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="control{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="control{{$i}}due" name="control{{$i}}due" value="@if(old('control{{$i}}due'))<?= old('control{{$i}}due') ?>@else<?= $project['duedates']['control'][$keys[$keycounter]]['due'] ?>@endif">
            </div>
            <input type="hidden" id="control{{$i}}name" name="control{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
            <?php $keycounter++; ?>
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="control{{$i}}">Remove Control Field</button>
          </div>
        </br>
        </div>
      <?php } ?>
    </div>
  @endif
  <input type="hidden" id="controlfields" name="controlfields" value="{{$controlfields}}" readonly />
  <div id = "addedcontrol">
  </div>

    <!-- Collection Line Drawing Package -->

    @if(!is_null($collectionfields) || !isset($project['duedates']))
      <!-- Heading for Collection Line Drawing Package, displays the title and second-level fields -->
      <div id="collectionheading">
        <h5><b> Collection Line Drawing Package</b></h5>
        <div class="row">
          <div>
            <button type="button" class="btn btn-danger btn_remove" id="removecollection">Remove All Collection Fields</button>
            <button style="margin:10px;" type="button" class="btn btn-warning" id="addcollection">Add New Collection Field</button> 
          </div>
        </br>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="collectionperson1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="collectionperson1" value="@if(old('collectionperson1')){{ old('collectionperson1') }} @else<?= $__env->yieldContent('collectionperson1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collectionperson2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="collectionperson2" name="collectionperson2" value="@if(old('collectionperson2'))<?= old('collectionperson2') ?>@else<?= $__env->yieldContent('collectionperson2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collectiondue">Due Date</label>
                <input type="date" class="form-control" id="collectiondue" name="collectiondue" value="@if(old('collectiondue'))<?= old('collectiondue') ?>@else<?= $__env->yieldContent('collectiondue')?>@endif">
            </div>
          </br>
        </div>
      </div>
    @endif
    @if(!isset($project['duedates']))
    <!-- Body for Collection Line Drawing Package if the project has not saved any due dates, displays third-level fields -->
      <div id="collectionbody">
        <?php $collectionfields = 1; ?>
        <h6 id="collection{{$collectionfields}}title" style="margin-left: 55px"><b>90</b></h6>
            <div id="collection{{$collectionfields}}row" style="margin-left: 40px" class="row">
                <div class="form-group col-md-4">
                <label for="collection{{$collectionfields}}person1">Engineer/Person 1</label>
                    <input type="text" class="form-control" name="collection{{$collectionfields}}person1" value="@if(old('collection{{$collectionfields}}person1'))<?= old('collection{{$collectionfields}}person1') ?>@else<?= $__env->yieldContent('collection{{$collectionfields}}person1')?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="collection{{$collectionfields}}person2">Drafter/Person 2</label>
                    <input type="text" class="form-control" id="collection{{$collectionfields}}person2" name="collection{{$collectionfields}}person2" value="@if(old('collection{{$collectionfields}}person2'))<?= old('collection{{$collectionfields}}person2') ?>@else<?= $__env->yieldContent('collection{{$collectionfields}}person2')?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="collection{{$collectionfields}}due">Due Date</label>
                    <input type="date" class="form-control" id="collection{{$collectionfields}}due" name="collection{{$collectionfields}}due" value="@if(old('collection{{$collectionfields}}due'))<?= old('collection{{$collectionfields}}due') ?>@else<?= $__env->yieldContent('collection{{$collectionfields}}due')?>@endif">
                </div>
                <input type="hidden" id="collection{{$collectionfields}}name" name="collection{{$collectionfields}}name" value="90" readonly />
              </br>
            </div>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-danger btn_remove" id="collection{{$collectionfields}}">Remove Collection Field</button>
              <?php $collectionfields++; ?>
            </div>
          </br>
          </div>
        <h6 id="collection{{$collectionfields}}title" style="margin-left: 55px"><b>IFC</b></h6>
          <div id="collection{{$collectionfields}}row" style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                  <label for="collection{{$collectionfields}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="collection{{$collectionfields}}person1" value="@if(old('collection{{$collectionfields}}person1'))<?= old('collection{{$collectionfields}}person1') ?> @else<?= $__env->yieldContent('collection{{$collectionfields}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
              <label for="collection{{$collectionfields}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="collection{{$collectionfields}}person2" name="collection{{$collectionfields}}person2" value="@if(old('collection{{$collectionfields}}person2'))<?= old('collection{{$collectionfields}}person2') ?>@else<?= $__env->yieldContent('collection{{$collectionfields}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="collection{{$collectionfields}}due">Due Date</label>
              <input type="date" class="form-control" id="collection{{$collectionfields}}due" name="collection{{$collectionfields}}due" value="@if(old('collection{{$collectionfields}}due'))<?= old('collection{{$collectionfields}}due') ?>@else<?= $__env->yieldContent('collection{{$collectionfields}}due')?>@endif">
              </div>
              <input type="hidden" id="collection{{$collectionfields}}name" name="collection{{$collectionfields}}name" value="IFC" readonly />
            </br>
          </div>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-danger btn_remove" id="collection{{$collectionfields}}">Remove Collection Field</button>
            </div>
          </div>
          </br>
      </div>
    @elseif($collectionfields > 0)
    <!-- Body for Collection Line Drawing Package if the project has saved due dates, displays third-level fields -->
      <div id="collectionbody">
        <?php $keycounter = 3; ?>
        <?php for($i = 1; $i <= $collectionfields; $i++){?>
          <?php $keys = array_keys($project['duedates']['collection']);?>
          <h6 id="collection{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
          <div id="collection{{$i}}row" style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                  <label for="collection{{$i}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" id="collection{{$i}}person1" name="collection{{$i}}person1" value="@if(old('collection{{$i}}person1'))<?= old('collection{{$i}}person1') ?>@else<?= $project['duedates']['collection'][$keys[$keycounter]]['person1'] ?>@endif">
              </div>
              <div class="form-group col-md-4">
                <label for="collection{{$i}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="collection{{$i}}person2" name="collection{{$i}}person2" value="@if(old('collection{{$i}}person2'))<?= old('collection{{$i}}person2') ?>@else<?= $project['duedates']['collection'][$keys[$keycounter]]['person2'] ?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="collection{{$i}}due">Due Date</label>
                  <input type="date" class="form-control" id="collection{{$i}}due" name="collection{{$i}}due" value="@if(old('collection{{$i}}due'))<?= old('collection{{$i}}due') ?>@else<?= $project['duedates']['collection'][$keys[$keycounter]]['due'] ?>@endif">
              </div>
              <input type="hidden" id="collection{{$i}}name" name="collection{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
              <?php $keycounter++; ?>
            </br>
          </div>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-danger btn_remove" id="collection{{$i}}">Remove Collection Field</button>
            </div>
          </br>
          </div>
        <?php } ?>
      </div>
    @endif
    <input type="hidden" id="collectionfields" name="collectionfields" value="{{$collectionfields}}" readonly />
    <div id= "addedcollection">
    </div>

  <!-- Transmission Line Drawing Package -->

  @if(!is_null($transmissionfields) || !isset($project['duedates']))
  <!-- Heading for Transmission Line Drawing Package, displays the title and second-level fields -->
    <div id="transmissionheading">
      <h5><b> Transmission Line Drawing Package</b></h5>
      <div class="row">
        <div>
          <button type="button" class="btn btn-danger btn_remove" id="removetransmission">Remove All Transmission Fields</button>
          <button style="margin:10px;" type="button" class="btn btn-warning" id="addtransmission">Add New Transmission Field</button> 
        </div>
      </br>
      </div>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="transmissionperson1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="transmissionperson1" value="@if(old('transmissionperson1')){{ old('transmissionperson1') }} @else<?= $__env->yieldContent('transmissionperson1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissionperson2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="transmissionperson2" name="transmissionperson2" value="@if(old('transmissionperson2'))<?= old('transmissionperson2') ?>@else<?= $__env->yieldContent('transmissionperson2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissiondue">Due Date</label>
              <input type="date" class="form-control" id="transmissiondue" name="transmissiondue" value="@if(old('transmissiondue'))<?= old('transmissiondue') ?>@else<?= $__env->yieldContent('transmissiondue')?>@endif">
          </div>
        </br>
      </div>
    </div>
  @endif
  @if(!isset($project['duedates']))
  <!-- Body for Transmission Line Drawing Package if the project has not saved any due dates, displays third-level fields -->
    <div id="transmissionbody">
      <?php $transmissionfields = 1; ?>
      <h6 id="transmission{{$transmissionfields}}title" style="margin-left: 55px"><b>90</b></h6>
          <div id="transmission{{$transmissionfields}}row" style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
              <label for="transmission{{$transmissionfields}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="transmission{{$transmissionfields}}person1" value="@if(old('transmission{{$transmissionfields}}person1'))<?= old('transmission{{$transmissionfields}}person1') ?>@else<?= $__env->yieldContent('transmission{{$transmissionfields}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="transmission{{$transmissionfields}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="transmission{{$transmissionfields}}person2" name="transmission{{$transmissionfields}}person2" value="@if(old('transmission{{$transmissionfields}}person2'))<?= old('transmission{{$transmissionfields}}person2') ?>@else<?= $__env->yieldContent('transmission{{$transmissionfields}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="transmission{{$transmissionfields}}due">Due Date</label>
                  <input type="date" class="form-control" id="transmission{{$transmissionfields}}due" name="transmission{{$transmissionfields}}due" value="@if(old('transmission{{$transmissionfields}}due'))<?= old('transmission{{$transmissionfields}}due') ?>@else<?= $__env->yieldContent('transmission{{$transmissionfields}}due')?>@endif">
              </div>
              <input type="hidden" id="transmission{{$transmissionfields}}name" name="transmission{{$transmissionfields}}name" value="90" readonly />
            </br>
          </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="transmission{{$transmissionfields}}">Remove Transmission Field</button>
            <?php $transmissionfields++; ?>
          </div>
        </br>
        </div>
      <h6 id="transmission{{$transmissionfields}}title" style="margin-left: 55px"><b>IFC</b></h6>
        <div id="transmission{{$transmissionfields}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="transmission{{$transmissionfields}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="transmission{{$transmissionfields}}person1" value="@if(old('transmission{{$transmissionfields}}person1'))<?= old('transmission{{$transmissionfields}}person1') ?> @else<?= $__env->yieldContent('transmission{{$transmissionfields}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
            <label for="transmission{{$transmissionfields}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="transmission{{$transmissionfields}}person2" name="transmission{{$transmissionfields}}person2" value="@if(old('transmission{{$transmissionfields}}person2'))<?= old('transmission{{$transmissionfields}}person2') ?>@else<?= $__env->yieldContent('transmission{{$transmissionfields}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission{{$transmissionfields}}due">Due Date</label>
            <input type="date" class="form-control" id="transmission{{$transmissionfields}}due" name="transmission{{$transmissionfields}}due" value="@if(old('transmission{{$transmissionfields}}due'))<?= old('transmission{{$transmissionfields}}due') ?>@else<?= $__env->yieldContent('transmission{{$transmissionfields}}due')?>@endif">
            </div>
            <input type="hidden" id="transmission{{$transmissionfields}}name" name="transmission{{$transmissionfields}}name" value="IFC" readonly />
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="transmission{{$transmissionfields}}">Remove Transmission Field</button>
          </div>
        </div>
        </br>
    </div>
  @elseif($transmissionfields > 0)
  <!-- Body for Transmission Line Drawing Package if the project has saved due dates, displays third-level fields -->
    <div id="transmissionbody">
      <?php $keycounter = 3; ?>
      <?php for($i = 1; $i <= $transmissionfields; $i++){?>
        <?php $keys = array_keys($project['duedates']['transmission']);?>
        <h6 id="transmission{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
        <div id="transmission{{$i}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="transmission{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" id="transmission{{$i}}person1" name="transmission{{$i}}person1" value="@if(old('transmission{{$i}}person1'))<?= old('transmission{{$i}}person1') ?>@else<?= $project['duedates']['transmission'][$keys[$keycounter]]['person1'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
              <label for="transmission{{$i}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="transmission{{$i}}person2" name="transmission{{$i}}person2" value="@if(old('transmission{{$i}}person2'))<?= old('transmission{{$i}}person2') ?>@else<?= $project['duedates']['transmission'][$keys[$keycounter]]['person2'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="transmission{{$i}}due" name="transmission{{$i}}due" value="@if(old('transmission{{$i}}due'))<?= old('transmission{{$i}}due') ?>@else<?= $project['duedates']['transmission'][$keys[$keycounter]]['due'] ?>@endif">
            </div>
            <input type="hidden" id="transmission{{$i}}name" name="transmission{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
            <?php $keycounter++; ?>
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="transmission{{$i}}">Remove Transmission Field</button>
          </div>
        </br>
        </div>
      <?php } ?>
    </div>
  @endif
  <input type="hidden" id="transmissionfields" name="transmissionfields" value="{{$transmissionfields}}" readonly />
  <div id="addedtransmission">
  </div>

  <!-- SCADA -->

  @if(!is_null($scadafields) || !isset($project['duedates']))
  <!-- Heading for SCADA, displays the title and second-level fields -->
    <div id="scadaheading">
      <h5><b> SCADA </b></h5>
      <div class="row">
        <div>
          <button type="button" class="btn btn-danger btn_remove" id="removescada">Remove All SCADA Fields</button>
          <button style="margin:10px;" type="button" class="btn btn-warning" id="addscada">Add New SCADA Field</button> 
        </div>
      </br>
      </div>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="scadaperson1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="scadaperson1" value="@if(old('scadaperson1')){{ old('scadaperson1') }} @else<?= $__env->yieldContent('scadaperson1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="scadaperson2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="scadaperson2" name="scadaperson2" value="@if(old('scadaperson2'))<?= old('scadaperson2') ?>@else<?= $__env->yieldContent('scadaperson2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="scadadue">Due Date</label>
              <input type="date" class="form-control" id="scadadue" name="scadadue" value="@if(old('scadadue'))<?= old('scadadue') ?>@else<?= $__env->yieldContent('scadadue')?>@endif">
          </div>
      </div>
    </div>
  </br>
  @endif
  @if(!isset($project['duedates']))
  <!-- Body for SCADA if the project has not saved any due dates, displays third-level fields as well as fourth-level under communication -->
    <div id="scadabody">
      <?php $scadafields = 1; ?>
      <h6 id="scada{{$scadafields}}title" style="margin-left: 55px"><b>RTAC/Networking Configuration File</b></h6>
          <div id="scada{{$scadafields}}row" style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
              <label for="scada{{$scadafields}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="scada{{$scadafields}}person1" value="@if(old('scada{{$scadafields}}person1'))<?= old('scada{{$scadafields}}person1') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="scada{{$scadafields}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="scada{{$scadafields}}person2" name="scada{{$scadafields}}person2" value="@if(old('scada{{$scadafields}}person2'))<?= old('scada{{$scadafields}}person2') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="scada{{$scadafields}}due">Due Date</label>
                  <input type="date" class="form-control" id="scada{{$scadafields}}due" name="scada{{$scadafields}}due" value="@if(old('scada{{$scadafields}}due'))<?= old('scada{{$scadafields}}due') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}due')?>@endif">
              </div>
              <input type="hidden" id="scada{{$scadafields}}name" name="scada{{$scadafields}}name" value="RTAC/Networking" readonly />
            </br>
          </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="scada{{$scadafields}}">Remove SCADA Field</button>
            <?php $scadafields++; ?>
          </div>
        </br>
        </div>
      <h6 id="scada{{$scadafields}}title" style="margin-left: 55px"><b>Field Work Dates</b></h6>
        <div id="scada{{$scadafields}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="scada{{$scadafields}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="scada{{$scadafields}}person1" value="@if(old('scada{{$scadafields}}person1'))<?= old('scada{{$scadafields}}person1') ?> @else<?= $__env->yieldContent('scada{{$scadafields}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
            <label for="scada{{$scadafields}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="scada{{$scadafields}}person2" name="scada{{$scadafields}}person2" value="@if(old('scada{{$scadafields}}person2'))<?= old('scada{{$scadafields}}person2') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="scada{{$scadafields}}due">Due Date</label>
            <input type="date" class="form-control" id="scada{{$scadafields}}due" name="scada{{$scadafields}}due" value="@if(old('scada{{$scadafields}}due'))<?= old('scada{{$scadafields}}due') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}due')?>@endif">
            </div>
            <input type="hidden" id="scada{{$scadafields}}name" name="scada{{$scadafields}}name" value="FieldWork" readonly />
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="scada{{$scadafields}}">Remove SCADA Field</button>
          </div>
          <?php $scadafields++; ?>
        </div>
        </br>
        <h6 id="scada{{$scadafields}}title" style="margin-left: 55px"><b>Communication Architecture</b></h6>
        <div id="scada{{$scadafields}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
            <label for="scada{{$scadafields}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="scada{{$scadafields}}person1" value="@if(old('scada{{$scadafields}}person1'))<?= old('scada{{$scadafields}}person1') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="scada{{$scadafields}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="scada{{$scadafields}}person2" name="scada{{$scadafields}}person2" value="@if(old('scada{{$scadafields}}person2'))<?= old('scada{{$scadafields}}person2') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="scada{{$scadafields}}due">Due Date</label>
                <input type="date" class="form-control" id="scada{{$scadafields}}due" name="scada{{$scadafields}}due" value="@if(old('scada{{$scadafields}}due'))<?= old('scada{{$scadafields}}due') ?>@else<?= $__env->yieldContent('scada{{$scadafields}}due')?>@endif">
            </div>
            <input type="hidden" id="scada{{$scadafields}}name" name="scada{{$scadafields}}name" value="Communication" readonly />
          </br>
        </div>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="scada{{$scadafields}}">Remove SCADA Field</button>
        </div>
        <?php $communicationfields = 1; ?>
      </div>
      <h6 id="communication{{$communicationfields}}title" style="margin-left: 95px"><b>90</b></h6>
      <div id="communication{{$communicationfields}}row" style="margin-left: 80px" class="row">
          <div class="form-group col-md-4">
          <label for="communication{{$communicationfields}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="communication{{$communicationfields}}person1" value="@if(old('communication{{$communicationfields}}person1'))<?= old('communication{{$communicationfields}}person1') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="communication{{$communicationfields}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="communication{{$communicationfields}}person2" name="communication{{$communicationfields}}person2" value="@if(old('communication{{$communicationfields}}person2'))<?= old('communication{{$communicationfields}}person2') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}person2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="communication{{$communicationfields}}due">Due Date</label>
              <input type="date" class="form-control" id="communication{{$communicationfields}}due" name="communication{{$communicationfields}}due" value="@if(old('communication{{$communicationfields}}due'))<?= old('communication{{$communicationfields}}due') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}due')?>@endif">
          </div>
          <input type="hidden" id="communication{{$communicationfields}}name" name="communication{{$communicationfields}}name" value="90" readonly />
        </br>
      </div>
    <div style="margin-left: 80px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="communication{{$communicationfields}}">Remove Field</button>
      </div>
      <?php $communicationfields++; ?>
    </div>
      <h6 id="communication{{$communicationfields}}title" style="margin-left: 95px"><b>IFC</b></h6>
      <div id="communication{{$communicationfields}}row" style="margin-left: 80px" class="row">
          <div class="form-group col-md-4">
          <label for="communication{{$communicationfields}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="communication{{$communicationfields}}person1" value="@if(old('communication{{$communicationfields}}person1'))<?= old('communication{{$communicationfields}}person1') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="communication{{$communicationfields}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="communication{{$communicationfields}}person2" name="communication{{$communicationfields}}person2" value="@if(old('communication{{$communicationfields}}person2'))<?= old('communication{{$communicationfields}}person2') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}person2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="communication{{$communicationfields}}due">Due Date</label>
              <input type="date" class="form-control" id="communication{{$communicationfields}}due" name="communication{{$communicationfields}}due" value="@if(old('communication{{$communicationfields}}due'))<?= old('communication{{$communicationfields}}due') ?>@else<?= $__env->yieldContent('communication{{$communicationfields}}due')?>@endif">
          </div>
          <input type="hidden" id="communication{{$communicationfields}}name" name="communication{{$communicationfields}}name" value="IFC" readonly />
        </br>
      </div>
    <div style="margin-left: 80px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="communication{{$communicationfields}}">Remove Field</button>
      </div>
    </div>
  </div>
  @elseif($scadafields > 0)
  <!-- Body for SCADA if the project has saved due dates, displays third-level fields as well as fourth-level under communication -->
    <div id="scadabody">
      <?php $keycounter = 3; ?>
      <?php for($i = 1; $i <= $scadafields; $i++){?>
        <?php $keys = array_keys($project['duedates']['scada']);?>
        <h6 id="scada{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
        <div id="scada{{$i}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="scada{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" id="scada{{$i}}person1" name="scada{{$i}}person1" value="@if(old('scada{{$i}}person1'))<?= old('scada{{$i}}person1') ?>@else<?= $project['duedates']['scada'][$keys[$keycounter]]['person1'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
              <label for="scada{{$i}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="scada{{$i}}person2" name="scada{{$i}}person2" value="@if(old('scada{{$i}}person2'))<?= old('scada{{$i}}person2') ?>@else<?= $project['duedates']['scada'][$keys[$keycounter]]['person2'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="scada{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="scada{{$i}}due" name="scada{{$i}}due" value="@if(old('scada{{$i}}due'))<?= old('scada{{$i}}due') ?>@else<?= $project['duedates']['scada'][$keys[$keycounter]]['due'] ?>@endif">
            </div>
            <input type="hidden" id="scada{{$i}}name" name="scada{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="scada{{$i}}">Remove Scada Field</button>
          </div>
        </br>
        </div>
        <?php if($keys[$keycounter] == 'Communication') { ?>
          <?php $comcounter = 3; ?>
          <?php for($j = 1; $j <= $communicationfields; $j++){?>
            <?php $comkeys = array_keys($project['duedates']['scada']['Communication']);?>
            <h6 id="communication{{$j}}title" style="margin-left: 95px"><b>{{$comkeys[$comcounter]}}</b></h6>
            <div id="communication{{$j}}row" style="margin-left: 80px" class="row">
                <div class="form-group col-md-4">
                    <label for="communication{{$j}}person1">Engineer/Person 1</label>
                    <input type="text" class="form-control" id="communication{{$j}}person1" name="communication{{$j}}person1" value="@if(old('communication{{$j}}person1'))<?= old('communication{{$j}}person1') ?>@else<?= $project['duedates']['scada']['Communication'][$comkeys[$comcounter]]['person1'] ?>@endif">
                </div>
                <div class="form-group col-md-4">
                  <label for="communication{{$j}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="communication{{$j}}person2" name="communication{{$j}}person2" value="@if(old('communication{{$j}}person2'))<?= old('communication{{$j}}person2') ?>@else<?= $project['duedates']['scada']['Communication'][$comkeys[$comcounter]]['person2'] ?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="communication{{$j}}due">Due Date</label>
                    <input type="date" class="form-control" id="communication{{$j}}due" name="communication{{$j}}due" value="@if(old('communication{{$j}}due'))<?= old('communication{{$j}}due') ?>@else<?= $project['duedates']['scada']['Communication'][$comkeys[$comcounter]]['due'] ?>@endif">
                </div>
                <input type="hidden" id="communication{{$j}}name" name="communication{{$j}}name" value="{{$comkeys[$comcounter]}}" readonly />
              </br>
            </div>
            <div style="margin-left: 80px" class="row">
              <div class="form-group col-md-4">
                <button type="button" class="btn btn-danger btn_remove" id="communication{{$j}}">Remove Field</button>
              </div>
              <?php $comcounter++; ?>
            </br>
            </div>
            <?php }  ?>
        <?php }  ?>
        <?php $keycounter++; ?>
      <?php } ?>
    </div>
  @endif
  <input type="hidden" id="scadafields" name="scadafields" value="{{$scadafields}}" readonly />
  <input type="hidden" id="communicationfields" name="communicationfields" value="{{$communicationfields}}" readonly />
  <div id="addedscada">
  </div>

  <!-- Studies -->

  @if(!is_null($totalstudies) || !isset($project['duedates']))
  <!-- Heading for Studies, displays the title and second-level fields -->
    <div id="studiesheading">
      <h5><b>Studies</b></h5>
      <div class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove All Studies</button>
          <button style="margin:10px;" type="button" class="btn btn-warning" id="addstudy">Add New Study</button>
        </div>
      </br>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
            <label for="studiesperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="studiesperson1" name="studiesperson1" value="@if(old('studiesperson1'))<?= old('studiesperson1') ?>@else<?= $__env->yieldContent('studiesperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="studiesdue">Due Date</label>
            <input type="date" class="form-control" id="studiesdue" name="studiesdue" value="@if(old('studiesdue'))<?= old('studiesdue') ?>@else<?= $__env->yieldContent('studiesdue')?>@endif">
        </div>
      </br>
      </div>
      </br>
    </div>
  @endif
  @if(!isset($project['duedates']))
  <!-- Body for Studies if the project has not saved any due dates, displays third-level fields -->
    <div id="studiesbody">
      <?php $totalstudies = 1; ?>
      <h6 id="study{{$totalstudies}}title" style="margin-left: 55px"><b>Reactive Study</b></h6>
      <div id="study{{$totalstudies}}row" style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" id="study{{$totalstudies}}person1" name="study{{$totalstudies}}person1" value="@if(old('study{{$totalstudies}}person1'))<?= old('study{{$totalstudies}}person1') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}due">Due Date</label>
              <input type="date" class="form-control" id="study{{$totalstudies}}due" name="study{{$totalstudies}}due" value="@if(old('study{{$totalstudies}}due'))<?= old('study{{$totalstudies}}due') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}due')?>@endif">
          </div>
          <input type="hidden" id="study{{$totalstudies}}name" name="study{{$totalstudies}}name" value="Reactive Study" readonly />
        </br>
      </div>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="study{{$totalstudies}}">Remove Study</button>
          <?php $totalstudies++ ?>
        </div>
      </br>
      </div>

      <h6 id="study{{$totalstudies}}title" style="margin-left: 55px"><b>Ampacity Study</b></h6>
      <div id="study{{$totalstudies}}row" style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" id="study{{$totalstudies}}person1" name="study{{$totalstudies}}person1" value="@if(old('study{{$totalstudies}}person1'))<?= old('study{{$totalstudies}}person1') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}due">Due Date</label>
              <input type="date" class="form-control" id="study{{$totalstudies}}due" name="study{{$totalstudies}}due" value="@if(old('study{{$totalstudies}}due'))<?= old('study{{$totalstudies}}due') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}due')?>@endif">
          </div>
          <input type="hidden" id="study{{$totalstudies}}name" name="study{{$totalstudies}}name" value="Ampacity Study" readonly />
        </br>
      </div>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="study{{$totalstudies}}">Remove Study</button>
          <?php $totalstudies++ ?>
        </div>
      </br>
      </div>

      <h6 id="study{{$totalstudies}}title" style="margin-left: 55px"><b>Load Flow Study</b></h6>
      <div id="study{{$totalstudies}}row" style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" id="study{{$totalstudies}}person1" name="study{{$totalstudies}}person1" value="@if(old('study{{$totalstudies}}person1'))<?= old('study{{$totalstudies}}person1') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}due">Due Date</label>
              <input type="date" class="form-control" id="study{{$totalstudies}}due" name="study{{$totalstudies}}due" value="@if(old('study{{$totalstudies}}due'))<?= old('study{{$totalstudies}}due') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}due')?>@endif">
          </div>
          <input type="hidden" id="study{{$totalstudies}}name" name="study{{$totalstudies}}name" value="Load Flow Study" readonly />
        </br>
      </div>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="study{{$totalstudies}}">Remove Study</button>
          <?php $totalstudies++ ?>
        </div>
      </br>
      </div>

      <h6 id="study{{$totalstudies}}title" style="margin-left: 55px"><b>Relay and Coordination Study</b></h6>
      <div id="study{{$totalstudies}}row" style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" id="study{{$totalstudies}}person1" name="study{{$totalstudies}}person1" value="@if(old('study{{$totalstudies}}person1'))<?= old('study{{$totalstudies}}person1') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="study{{$totalstudies}}due">Due Date</label>
              <input type="date" class="form-control" id="study{{$totalstudies}}due" name="study{{$totalstudies}}due" value="@if(old('study{{$totalstudies}}due'))<?= old('study{{$totalstudies}}due') ?>@else<?= $__env->yieldContent('study{{$totalstudies}}due')?>@endif">
          </div>
          <input type="hidden" id="study{{$totalstudies}}name" name="study{{$totalstudies}}name" value="Relay and Coordination Study" readonly />
        </br>
      </div>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="study{{$totalstudies}}">Remove Study</button>
        </div>
      </br>
      </div>
      </br>
    </div>
  @elseif ($totalstudies > 0)
  <!-- Body for Studies if the project has saved due dates, displays third-level fields -->
    <div id="studiesbody">
      <?php $keycounter = 2; ?>
      <?php for($i = 1; $i <= $totalstudies; $i++){?>
        <?php $keys = array_keys($project['duedates']['studies']);?>
        <h6 id="study{{$i}}title" style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
        <div id="study{{$i}}row" style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="study{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" id="study{{$i}}person1" name="study{{$i}}person1" value="@if(old('study{{$i}}person1'))<?= old('study{{$i}}person1') ?>@else<?= $project['duedates']['studies'][$keys[$keycounter]]['person1'] ?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="study{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="study{{$i}}due" name="study{{$i}}due" value="@if(old('study{{$i}}due'))<?= old('study{{$i}}due') ?>@else<?= $project['duedates']['studies'][$keys[$keycounter]]['due'] ?>@endif">
            </div>
            <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value="{{$keys[$keycounter]}}" readonly />
            <?php $keycounter++; ?>
          </br>
        </div>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="study{{$i}}">Remove Study</button>
          </div>
        </br>
        </div>
      <?php } ?>
    </div>
  @endif
  <input type="hidden" id="totalstudies" name="totalstudies" value="{{$totalstudies}}" readonly />
  <div id="addedstudy">
  </div>



    <div id="dynamic_field">
    <?php
    $c = 0;
    $misccount = 0;
    //Displays the miscellaneous fields that have been saved into the Database
    if(isset($project['duedates']['additionalfields'])) {
      $additionalfields = $project['duedates']['additionalfields'];
      $keys = array_keys($additionalfields);
      foreach($keys as $key){
        $c = $c + 1;
        ?>
        <div id="misc{{$c}}heading">
        <h5 id="name{{$c}}"><b> {{$key}} </b></h5>
                <div class="row">
                  <div class="form-group col-md-4">
                    <button type="button" class="btn btn-danger btn_remove" id="{{$c}}">Remove {{$key}} Form</button>
                    <button type="button" class="btn btn-warning" id="{{$c}}">Add To {{$key}}</button>
                  </div>
                </div> 
                <div class="row" id= "row{{$c}}">
                    <div class="form-group col-md-4"> 
                        <label for="row{{$c}}person1">Engineer/Person 1</label> 
                        <input type="text" class="form-control" id="row{{$c}}person1" name= "row{{$c}}person1" value= {{$additionalfields[$key]['person1']}}>
                    </div>
                    <div class="form-group col-md-4"> 
                        <label for="row{{$c}}person2">Drafter/Person 2</label> 
                        <input type="text" class="form-control" id="row{{$c}}person2" name="row{{$c}}person2" value= {{$additionalfields[$key]['person2']}}> 
                    </div> 
                    <div class="form-group col-md-4"> 
                        <label for="row{{$c}}due">Due Date</label> 
                        <input type="date" class="form-control" id="row{{$c}}due" name="row{{$c}}due" value= {{$additionalfields[$key]['due']}}>
                    </div>
                      <input type="hidden" id="row{{$c}}name" name="row{{$c}}name" value= "{{$key}}" readonly /> 
                </div>
              </div>
              <div id="misc{{$c}}body">
                <?php 
                $subkeys = array_keys($additionalfields[$key]);
                foreach($subkeys as $subkey){
                  if ($subkey != "person1" && $subkey != "person2" && $subkey != "due"){
                    $misccount = $misccount + 1;
                ?>
                <h6 style="margin-left: 55px" id="{{$c}}misc{{$misccount}}title" ><b>{{$subkey}}</b></h6>
                <div style="margin-left: 40px" class="row" id="{{$c}}misc{{$misccount}}row" >
                    <div class="form-group col-md-4"> 
                        <label for="{{$c}}misc{{$misccount}}person1">Engineer/Person 1</label>
                        <input type="text" class="form-control" id="{{$c}}misc{{$misccount}}person1" name="{{$c}}misc{{$misccount}}person1" value= {{$additionalfields[$key][$subkey]['person1']}}>
                    </div>
                    <div class="form-group col-md-4"> 
                        <label for="{{$c}}misc{{$misccount}}person2">Drafter/Person 2</label> 
                        <input type="text" class="form-control" id="{{$c}}misc{{$misccount}}person2" name="{{$c}}misc{{$misccount}}person2" value= {{$additionalfields[$key][$subkey]['person2']}}> 
                    </div> 
                    <div class="form-group col-md-4"> 
                        <label for="{{$c}}misc{{$misccount}}due">Due Date</label>
                        <input type="date" class="form-control" id="{{$c}}misc{{$misccount}}due" name="{{$c}}misc{{$misccount}}due" value= {{$additionalfields[$key][$subkey]['due']}}>
                    </div> 
                </div>
                    <input type="hidden" id="{{$c}}misc{{$misccount}}name" name="{{$c}}misc{{$misccount}}name" value="{{$subkey}}" readonly />
                    </br>
                    <div class="row">
                      <div style="margin-left: 55px" class="form-group col-md-4">
                        <button type="button" class="btn btn-danger btn_remove" id="{{$c}}misc{{$misccount}}">Remove Field</button>
                      </div>
                    </div> 
                    <?php } ?>
                <?php } ?>
              </div>
              <div id= "addedmisc{{$c}}">
              </div>
  <?php } ?>
  <input type="hidden" id="miscfields" name="miscfields" value="{{$misccount}}" readonly />
    <input type="hidden" id="total" name="total" value="{{$c}}" readonly />
   <?php } ?>

    </div>
    </br>
    <h5><b>Miscellaneous Add Form</b></h5>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-warning" id="addform">Add Form</button>
      </div>
    </div>
    </br>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>
  </form>

<!-- Javascript Start -->

<script type="text/javascript">
//initializes field variables passed from the php code
var row = "<?php echo $c ?>";
var total = parseInt(row);
var miscfields = parseInt("<?php echo $miscfields ?>");
//var miscnames = [];
var physicalfields = parseInt("<?php echo $physicalfields ?>");
var controlfields = parseInt("<?php echo $controlfields ?>");
var collectionfields = parseInt("<?php echo $collectionfields ?>");
var transmissionfields = parseInt("<?php echo $transmissionfields ?>");
var scadafields = parseInt("<?php echo $scadafields ?>");
var communicationfields = parseInt("<?php echo $communicationfields ?>");
var totalstudies = parseInt("<?php echo $totalstudies ?>");
//function that contains every JS function for the page. Says that the document is ready to take action on input
$(document).ready(function() {

//adds a row of miscellaneous forms when the miscellaneous add button is pressed
$("#addform").on('click', function() {
  var name = window.prompt('Enter the name of the new Field: ');
            if (name != null && name != ""){
                //miscnames.push(name);
                total ++;
                var field = '<div id="misc'+total+'heading">' +
                '<h5 id= name' + total+ '><b>' + name + '</b></h5>' + 
                      '<div class="row">' +
                        '<div class="form-group col-md-4">' +
                          '<button type="button" class="btn btn-danger btn_remove" id="'+total+'">Remove '+name+' Forms</button>'+
                          '<button style="margin:10px;" type="button" class="btn btn-warning" id="'+total+'">Add To '+name+'</button>' +
                        '</div>' +
                      '</div>'+
                      '<div id="row'+total+'" class="row" >' + 
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+total+'person1">Engineer/Person 1</label>' + 
                            '<input type="text" class="form-control" id="row'+ total+'person1" name="row'+ total+'person1">' +
                        '</div>' +
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+ total+'person2">Drafter/Person 2</label>' + 
                            '<input type="text" class="form-control" id="row'+ total+'person2" name="row'+ total+'person2">' + 
                        '</div>' + 
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+ total+'due">Due Date</label>' + 
                            '<input type="date" class="form-control" id="row'+ total+'due" name="row'+ total+'due">' + 
                        '</div>' + 
                        '<input type="hidden" id="row'+ total+'name" name="row'+ total+'name" value="'+ name + '" readonly />' +
                      '</div>' +
                      '</div>' +
                      '<div id="addedmisc'+total+'">' +
                      '</div>';
                hiddenfield();
                $('#dynamic_field').append(field);  
                        }
});

//adds a row of third level forms when the add button by the second level miscellaneous field is pressed
$("#dynamic_field").on('click', '.btn-warning', function() {
      var button_id = $(this).attr("id");
      var name = window.prompt('Enter the name of the new Subfield: ');
      if (name != null && name != ""){
        miscfields++;
        var field = '<h6 style="margin-left: 55px" id="'+button_id+'misc' +miscfields+ 'title" ><b>' + name + '</b></h6>' +
                        '<div style="margin-left: 40px" class="row" id="'+button_id+'misc'+miscfields+'row">' + 
                            '<div class="form-group col-md-4">' + 
                                '<label for="'+button_id+'misc'+miscfields+'person1">Engineer/Person 1</label>' + 
                                '<input type="text" class="form-control" id="'+button_id+'misc'+miscfields+'person1" name="'+button_id+'misc'+miscfields+'person1">' +
                            '</div>' +
                            '<div class="form-group col-md-4">' + 
                                '<label for="'+button_id+'misc'+miscfields+'person2">Drafter/Person 2</label>' + 
                                '<input type="text" class="form-control" id="'+button_id+'misc'+miscfields+'person2" name="'+button_id+'misc'+miscfields+'person2">' + 
                            '</div>' + 
                            '<div class="form-group col-md-4">' + 
                                '<label for="'+button_id+'misc'+miscfields+'due">Due Date</label>' + 
                                '<input type="date" class="form-control" id="'+button_id+'misc'+miscfields+'due" name="'+button_id+'misc'+miscfields+'due">' + 
                            '</div>' + 
                        '</div>' +
                            '<input type="hidden" id="'+button_id+'misc'+miscfields+'name" name="'+button_id+'misc'+miscfields+'name" value="'+ name + '" readonly />' +
                            '</br>' +
                            '<div class="row">' +
                              '<div style="margin-left: 55px" class="form-group col-md-4">' +
                                '<button type="button" class="btn btn-primary" id="'+button_id+'misc'+miscfields+'">Remove Field</button>'+
                              '</div>' +
                            '</div>'; 
                    miscfield();
                    $('#addedmisc'+button_id).append(field);
      }
    }); 

//removes an existing third row field under the miscellaneous category
for (i = 1; i <= total; i++){
  for(j = 1; j <= miscfields; j++){
    $("#"+ i +"misc" + j).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }
}

//removes a third row field that has just been added under the miscellaneous category
  $("#dynamic_field").on('click', '.btn-primary', function() {
    var button_id = $(this).attr("id");
    $('#'+button_id+'title').remove();
    $('#'+button_id+'name').remove();
    $('#'+button_id+'row').remove();
    $('#'+button_id+'').remove();
  });
 

//adds a new row of third level fields under the Physical Drawing Package
$("#addphysical").on('click', function() {
                var name = window.prompt('Enter the name of the new Physical field: ');
                if (name != null && name != ""){
                  physicalfields ++;
                  var field = '<h6  style="margin-left: 55px" id="physical' +physicalfields+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "physical'+physicalfields+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="physical'+physicalfields+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="physical'+ physicalfields+'person1" name="physical'+ physicalfields+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="physical'+ physicalfields+'person2">Drafter/Person 2</label>' + 
                              '<input type="text" class="form-control" id="physical'+ physicalfields+'person2" name="physical'+ physicalfields+'person2">' + 
                          '</div>' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="physical'+ physicalfields+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="physical'+ physicalfields+'due" name="physical'+ physicalfields+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="physical'+ physicalfields+'name" name="physical'+ physicalfields+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="physical'+physicalfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  physicalfield();
                  $('#addedphysical').append(field);
                } 
});

//removes an existing third row field under the Physical Drawing Package
  for(i = 1; i <= physicalfields; i++){
    $("#physical" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

//removes a third row field that has just been added under the Physical Drawing Package
  $("#addedphysical").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 

//adds a new row of third level fields under the Wiring and Controls Drawing Package
$("#addcontrol").on('click', function() {
                var name = window.prompt('Enter the name of the new Control field: ');
                if (name != null && name != ""){
                  controlfields ++;
                  var field = '<h6  style="margin-left: 55px" id= "control' + controlfields+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "control'+controlfields+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="control'+controlfields+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="control'+ controlfields+'person1" name="control'+ controlfields+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="control'+ controlfields+'person2">Drafter/Person 2</label>' + 
                              '<input type="text" class="form-control" id="control'+ controlfields+'person2" name="control'+ controlfields+'person2">' + 
                          '</div>' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="control'+ controlfields+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="control'+ controlfields+'due" name="control'+ controlfields+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="control'+ controlfields+'name" name="control'+ controlfields+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="control'+controlfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  controlfield();
                  $('#addedcontrol').append(field);
                } 
});

//removes an existing third row field under the Wiring and Controls Drawing Package
for(i = 1; i <= controlfields; i++){
    $("#control" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

//removes a third row field that has just been added under the Wiring and Controls Drawing Package
  $("#addedcontrol").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

//adds a new row of third level fields under the Collection Line Drawing Package
$("#addcollection").on('click', function() {
                var name = window.prompt('Enter the name of the new Collection field: ');
                if (name != null && name != ""){
                  collectionfields ++;
                  var field = '<h6  style="margin-left: 55px" id= "collection' + collectionfields+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "collection'+collectionfields+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="collection'+collectionfields+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="collection'+ collectionfields+'person1" name="collection'+ collectionfields+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="collection'+ collectionfields+'person2">Drafter/Person 2</label>' + 
                              '<input type="text" class="form-control" id="collection'+ collectionfields+'person2" name="collection'+ collectionfields+'person2">' + 
                          '</div>' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="collection'+ collectionfields+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="collection'+ collectionfields+'due" name="collection'+ collectionfields+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="collection'+ collectionfields+'name" name="collection'+ collectionfields+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="collection'+collectionfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  collectionfield();
                  $('#addedcollection').append(field);
                } 
});

//removes an existing third row field under the Collection Line Drawing Package
for(i = 1; i <= collectionfields; i++){
    $("#collection" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

//removes a third row field that has just been added under the Collection Line Drawing Package
  $("#addedcollection").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

//adds a new row of third level fields under the Transmission Line Drawing Package
$("#addtransmission").on('click', function() {
                var name = window.prompt('Enter the name of the new Transmission field: ');
                if (name != null && name != ""){
                  transmissionfields ++;
                  var field = '<h6  style="margin-left: 55px" id= "transmission' + transmissionfields+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "transmission'+transmissionfields+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="transmission'+transmissionfields+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="transmission'+ transmissionfields+'person1" name="transmission'+ transmissionfields+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="transmission'+ transmissionfields+'person2">Drafter/Person 2</label>' + 
                              '<input type="text" class="form-control" id="transmission'+ transmissionfields+'person2" name="transmission'+ transmissionfields+'person2">' + 
                          '</div>' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="transmission'+ transmissionfields+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="transmission'+ transmissionfields+'due" name="transmission'+ transmissionfields+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="transmission'+ transmissionfields+'name" name="transmission'+ transmissionfields+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="transmission'+transmissionfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  transmissionfield();
                  $('#addedtransmission').append(field);
                } 
});

//removes an existing third row field under the Transmission Line Drawing Package
for(i = 1; i <= transmissionfields; i++){
    $("#transmission" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

//removes a third row field that has just been added under the Transmission Line Drawing Package
  $("#addedtransmission").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

//adds a new row of third level fields under the SCADA category
$("#addscada").on('click', function() {
                var name = window.prompt('Enter the name of the new SCADA field: ');
                if (name != null && name != ""){
                  scadafields ++;
                  var field = '<h6  style="margin-left: 55px" id= "scada' + scadafields+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "scada'+scadafields+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="scada'+scadafields+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="scada'+ scadafields+'person1" name="scada'+ scadafields+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="scada'+ scadafields+'person2">Drafter/Person 2</label>' + 
                              '<input type="text" class="form-control" id="scada'+ scadafields+'person2" name="scada'+ scadafields+'person2">' + 
                          '</div>' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="scada'+ scadafields+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="scada'+ scadafields+'due" name="scada'+ scadafields+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="scada'+ scadafields+'name" name="scada'+ scadafields+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="scada'+scadafields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  scadafield();
                  $('#addedscada').append(field);
                } 
});

//removes an existing third row field under the SCADA category
for(i = 1; i <= scadafields; i++){
    $("#scada" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

//Removes fourth level fields that are under the Communication category in SCADA
  for(i = 1; i <= communicationfields; i++){
    $("#communication" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });
  }

//removes a third row field that has just been added under the SCADA category
  $("#addedscada").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

//adds a new row of third level fields under the Studies category
$("#addstudy").on('click', function() {
                var name = window.prompt('Enter the name of the new study: ');
                if (name != null && name != ""){
                  totalstudies ++;
                  var field = '<h6  style="margin-left: 55px" id= "study' + totalstudies+ 'title" ><b>' + name + '</b></h6>' +
                      '<div style="margin-left: 40px" class="row" id = "study'+totalstudies+'row" >' + 
                          '<div class="form-group col-md-4">' + 
                              '<label for="study'+totalstudies+'person1">Engineer/Person 1</label>' + 
                              '<input type="text" class="form-control" id="study'+ totalstudies+'person1" name="study'+ totalstudies+'person1">' +
                          '</div>' +
                          '<div class="form-group col-md-4">' + 
                              '<label for="study'+ totalstudies+'due">Due Date</label>' + 
                              '<input type="date" class="form-control" id="study'+ totalstudies+'due" name="study'+ totalstudies+'due">' + 
                          '</div>' + 
                          '</div>' +
                          '<input type="hidden" id="study'+ totalstudies+'name" name="study'+ totalstudies+'name" value="'+ name + '" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-primary" id="study'+totalstudies+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  studyfield();
                  $('#addedstudy').append(field);
                } 
});

//removes an existing third row field under the Studies category
for(i = 1; i <= totalstudies; i++){
    $("#study" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }
//removes a third row field that has just been added under the Studies category
  $("#addedstudy").on('click', '.btn-primary', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

//functions that remove an entire category of default tasks 

  $('#removephysical').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire Physical field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#physicalheading').remove();
      $('#addedphysical').remove();
      $('#physicalbody').remove();
      physicalfields = null;
      physicalfield();
    }
  });

  $('#removecontrol').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire Control field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#controlheading').remove();
      $('#addedcontrol').remove();
      $('#controlbody').remove();
      controlfields = null;
      controlfield();
    }
  });

  $('#removecollection').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire Collection field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#collectionheading').remove();
      $('#addedcollection').remove();
      $('#collectionbody').remove();
      collectionfields = null;
      collectionfield();
    }
  });

  $('#removetransmission').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire Transmission field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#transmissionheading').remove();
      $('#addedtransmission').remove();
      $('#transmissionbody').remove();
      transmissionfields = null;
      transmissionfield();
    }
  });

  $('#removescada').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire SCADA field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#scadaheading').remove();
      $('#addedscada').remove();
      $('#scadabody').remove();
      scadafields = null;
      scadafield();
    }
  });

  $('#removestudies').on('click', function() {
    var c = window.confirm("Are you sure you want to remove the entire Studies field?");
    if(c == true){
      var button_id = $(this).attr("id");
      $('#studiesheading').remove();
      $('#addedstudy').remove();
      $('#studiesbody').remove();
      totalstudies = null;
      studyfield();
    }
  });

  //removes an entire miscellaneous field along with its third level fields
$("#dynamic_field").on('click', '.btn_remove', function() {
  var c = window.confirm("Are you sure you want to remove the entire Miscellaneous field?");
  if(c == true){
    var button_id = $(this).attr("id");
    $('#misc'+button_id+'heading').remove();
    $('#misc'+button_id+'body').remove();
    $('#addedmisc'+button_id+'').remove();
  }
});

});

//Helper functions that replace the field counter hidden variables with updated values whenever called

function hiddenfield(){
  $('#total').remove();
  var field = '<input type="hidden" id="total" name="total" value="'+total+'" readonly />';
  $('#dynamic_field').append(field); 
}

function miscfield(){
  $('#miscfields').remove();
  var field = '<input type="hidden" id="miscfields" name="miscfields" value="'+miscfields+'" readonly />';
  $('#dynamic_field').append(field);
}

function physicalfield(){
  $('#physicalfields').remove();
  var field = '<input type="hidden" id="physicalfields" name="physicalfields" value="'+physicalfields+'" readonly />';
  $('#addedphysical').append(field); 
}

function controlfield(){
  $('#controlfields').remove();
  var field =  '<input type="hidden" id="controlfields" name="controlfields" value="'+controlfields+'" readonly />';
  $('#addedcontrol').append(field); 
}

function collectionfield(){
  $('#collectionfields').remove();
  var field = '<input type="hidden" id="collectionfields" name="collectionfields" value="'+collectionfields+'" readonly />';
  $('#addedcollection').append(field); 
}

function transmissionfield(){
  $('#transmissionfields').remove();
  var field = '<input type="hidden" id="transmissionfields" name="transmissionfields" value="'+transmissionfields+'" readonly />';
  $('#addedtransmission').append(field);
}

function scadafield(){
  $('#scadafields').remove();
  var field = '<input type="hidden" id="scadafields" name="scadafields" value="'+scadafields+'" readonly />';
  $('#addedscada').append(field);
}

function studyfield(){
  $('#totalstudies').remove();
  var field = '<input type="hidden" id="totalstudies" name="totalstudies" value="'+totalstudies+'" readonly />';
  $('#addedstudy').append(field);
}

</script>
  </body>
</html> 
