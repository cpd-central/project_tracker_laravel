<!doctype html>
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
        @if($physicalfields >= 0 || !isset($project['duedates']['physical']))
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
      @endif
      @if(!isset($project['duedates']['physical']))
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
          <input type="hidden" id="physicalfields" name="physicalfields" value={{$physicalfields}} readonly />
          </br>
          </br>
      @elseif($physicalfields > 0)
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
            <input type="hidden" id="physical{{$i}}name" name="physical{{$i}}name" value={{$keys[$keycounter]}} readonly />
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
      <input type="hidden" id="physicalfields" name="physicalfields" value={{$physicalfields}} readonly />
      @endif
      <input type="hidden" id="physicalfields" name="physicalfields" value={{$physicalfields}} readonly />
      <div id = "addedphysical">
      </div>


    @if($controlfields >= 0 || !isset($project['duedates']['control']))
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
  @endif
  @if(!isset($project['duedates']['control']))
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
      <input type="hidden" id="controlfields" name="controlfields" value={{$controlfields}} readonly />
      </br>
      </br>
  @elseif($controlfields > 0)
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
        <input type="hidden" id="control{{$i}}name" name="control{{$i}}name" value={{$keys[$keycounter]}} readonly />
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
  <input type="hidden" id="controlfields" name="controlfields" value={{$controlfields}} readonly />
  @endif
  <input type="hidden" id="controlfields" name="controlfields" value={{$controlfields}} readonly />
  <div id = "addedcontrol">
  </div>


      @if($collectionfields >= 0 || !isset($project['duedates']['collection']))
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
    @endif
    @if(!isset($project['duedates']['collection']))
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
        <input type="hidden" id="collectionfields" name="collectionfields" value={{$collectionfields}} readonly />
        </br>
        </br>
    @elseif($collectionfields > 0)
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
          <input type="hidden" id="collection{{$i}}name" name="collection{{$i}}name" value={{$keys[$keycounter]}} readonly />
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
    <input type="hidden" id="collectionfields" name="collectionfields" value={{$collectionfields}} readonly />
    @endif
    <input type="hidden" id="collectionfields" name="collectionfields" value={{$collectionfields}} readonly />
    <div id= "addedcollection">
    </div>



  @if($transmissionfields >= 0 || !isset($project['duedates']['transmission']))
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
  @endif
  @if(!isset($project['duedates']['transmission']))
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
      <input type="hidden" id="transmissionfields" name="transmissionfields" value={{$transmissionfields}} readonly />
      </br>
      </br>
  @elseif($transmissionfields > 0)
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
        <input type="hidden" id="transmission{{$i}}name" name="transmission{{$i}}name" value={{$keys[$keycounter]}} readonly />
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
  <input type="hidden" id="transmissionfields" name="transmissionfields" value={{$transmissionfields}} readonly />
  @endif
  <input type="hidden" id="transmissionfields" name="transmissionfields" value={{$transmissionfields}} readonly />
  <div id="addedtransmission">
  </div>


  @if($scadafields >= 0 || !isset($project['duedates']['scada']))
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
    @if(!isset($project['duedates']['scada']))
      <?php $scadafields = 0; ?>
    @endif
    <input type="hidden" id="scadafields" name="scadafields" value={{$scadafields}} readonly />
  </br>
  @endif
  @if($scadafields > 0)
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
        <input type="hidden" id="scada{{$i}}name" name="scada{{$i}}name" value={{$keys[$keycounter]}} readonly />
        <?php $keycounter++; ?>
      </br>
    </div>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="scada{{$i}}">Remove Scada Field</button>
      </div>
    </br>
    </div>
  <?php } ?>
  <input type="hidden" id="scadafields" name="scadafields" value={{$scadafields}} readonly />
  @endif
  <input type="hidden" id="scadafields" name="scadafields" value={{$scadafields}} readonly />
  <div id="addedscada">
  </div>

  <!-- Studies Starts -->

  @if($totalstudies >= 0 || !isset($project['duedates']['studies']))
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
  @endif
  @if(!isset($project['duedates']['studies']))
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
    <input type="hidden" id="totalstudies" name="totalstudies" value={{$totalstudies}} readonly />
    </br>
    </br>

  @elseif ($totalstudies > 0)
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
          <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value={{$keys[$keycounter]}} readonly />
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
    <input type="hidden" id="totalstudies" name="totalstudies" value={{$totalstudies}} readonly />
  @endif
  <input type="hidden" id="totalstudies" name="totalstudies" value={{$totalstudies}} readonly />
  <div id="addedstudy">
  </div>



    <div id="dynamic_field">
    <?php
    $c = 1;
    if(isset($project['duedates']['additionalfields'])) {
      $additionalfields = $project['duedates']['additionalfields'];
      $keys = array_keys($additionalfields);
      foreach($keys as $key){
        ?>
        <h5 id="name{{$c}}"><b> {{$key}} </b></h5> 
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
                    <div class="row">
                      <div class="form-group col-md-4">
                        <button type="button" class="btn btn-danger btn_remove" id="{{$c}}">Remove Form</button>
                      </div>
                      </div>
  <?php 
    $c = $c + 1;
     }
    } 
    ?> 

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

<script type="text/javascript">
var row = "<?php echo $c ?>" -1;
var total = parseInt(row);

var physicalfields = parseInt("<?php echo $physicalfields ?>");
var controlfields = parseInt("<?php echo $controlfields ?>");
var collectionfields = parseInt("<?php echo $collectionfields ?>");
var transmissionfields = parseInt("<?php echo $transmissionfields ?>");
var scadafields = parseInt("<?php echo $scadafields ?>");
var totalstudies = parseInt("<?php echo $totalstudies ?>");

$(document).ready(function() {

$("#addform").on('click', function() {
  var name = window.prompt('Enter the name of the new Field: ');
            if (name != null && name != ""){
                total ++;
                var field = '<h5 id= name' + total+ '><b>' + name + '</b></h5>' + 
                    '<div class="row" id = row' + total + '>' + 
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
                        '<div class="row">' +
                        '<div class="form-group col-md-4">' +
                          '<button type="button" class="btn btn-danger btn_remove" id="'+total+'">Remove Form</button>'+
                        '</div>' +
                        '</div>';
                hiddenfield();
                $('#dynamic_field').append(field);  
                        }
});

$("#dynamic_field").on('click', '.btn_remove', function() {
  var button_id = $(this).attr("id");
  $('#row'+button_id+'').remove();
  $('#name'+button_id+'').remove();
  $('#'+button_id+'').remove();
  hiddenfield();
}); 

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
                          '<input type="hidden" id="physicalfields" name="physicalfields" value="'+physicalfields+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="physical'+physicalfields+'">Remove New Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedphysical').append(field);
                } 
});

  for(i = 1; i <= physicalfields; i++){
    $("#physical" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedphysical").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 

$("#addcontrol").on('click', function() {
                var name = window.prompt('Enter the name of the new Control field: ');
                if (name != null && name != ""){
                  controlfields ++;
                //var button = document.getElementById('addphysical');
                //button.parentNode.removeChild(button);
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
                          '<input type="hidden" id="controlfields" name="controlfields" value="'+controlfields+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="control'+controlfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedcontrol').append(field);
                } 
});


for(i = 1; i <= controlfields; i++){
    $("#control" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedcontrol").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

$("#addcollection").on('click', function() {
                var name = window.prompt('Enter the name of the new Collection field: ');
                if (name != null && name != ""){
                  collectionfields ++;
                //var button = document.getElementById('addphysical');
                //button.parentNode.removeChild(button);
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
                          '<input type="hidden" id="collectionfields" name="collectionfields" value="'+collectionfields+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="collection'+collectionfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedcollection').append(field);
                } 
});

for(i = 1; i <= collectionfields; i++){
    $("#collection" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedcollection").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

$("#addtransmission").on('click', function() {
                var name = window.prompt('Enter the name of the new Transmission field: ');
                if (name != null && name != ""){
                  transmissionfields ++;
                //var button = document.getElementById('addphysical');
                //button.parentNode.removeChild(button);
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
                          '<input type="hidden" id="transmissionfields" name="transmissionfields" value="'+transmissionfields+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="transmission'+transmissionfields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedtransmission').append(field);
                } 
});

for(i = 1; i <= transmissionfields; i++){
    $("#transmission" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedtransmission").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

$("#addscada").on('click', function() {
                var name = window.prompt('Enter the name of the new SCADA field: ');
                if (name != null && name != ""){
                  scadafields ++;
                //var button = document.getElementById('addphysical');
                //button.parentNode.removeChild(button);
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
                          '<input type="hidden" id="scadafields" name="scadafields" value="'+scadafields+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="scada'+scadafields+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedscada').append(field);
                } 
});

for(i = 1; i <= scadafields; i++){
    $("#scada" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedscada").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

$("#addstudy").on('click', function() {
                var name = window.prompt('Enter the name of the new study: ');
                if (name != null && name != ""){
                  totalstudies ++;
                //var button = document.getElementById('addphysical');
                //button.parentNode.removeChild(button);
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
                          '<input type="hidden" id="totalstudies" name="totalstudies" value="'+totalstudies+'" readonly />' +
                          '</br>' +
                          '<div class="row">' +
                          '<div style="margin-left: 55px" class="form-group col-md-4">' +
                            '<button type="button" class="btn btn-danger btn_remove" id="study'+totalstudies+'">Remove Fields</button>'+
                          '</div>' +
                          '</div>'; 
                  $('#addedstudy').append(field);
                } 
});

for(i = 1; i <= totalstudies; i++){
    $("#study" + i).on('click', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    }); 
  }

  $("#addedstudy").on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#'+button_id+'title').remove();
      $('#'+button_id+'name').remove();
      $('#'+button_id+'row').remove();
      $('#'+button_id+'').remove();
    });

});

function hiddenfield(){
  $('#total').remove();
  var field = '<input type="hidden" id="total" name="total" value="'+total+'" readonly />';
  $('#dynamic_field').append(field); 
}

</script>
  </body>
</html> 
