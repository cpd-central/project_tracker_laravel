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
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="removephysical">Remove All Physical Fields</button>
          </div>
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
        </div>
        </br>
      @endif
      @if(!isset($project['duedates']['physical']))
        <?php $i = 1; ?>
        <h6 style="margin-left: 55px"><b>90</b></h6>
            <div style="margin-left: 40px" class="row">
                <div class="form-group col-md-4">
                <label for="physical{{$i}}person1">Engineer/Person 1</label>
                    <input type="text" class="form-control" name="physical{{$i}}person1" value="@if(old('physical{{$i}}person1'))<?= old('physical{{$i}}person1') ?>@else<?= $__env->yieldContent('physical{{$i}}person1')?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="physical{{$i}}person2">Drafter/Person 2</label>
                    <input type="text" class="form-control" id="physical{{$i}}person2" name="physical{{$i}}person2" value="@if(old('physical{{$i}}person2'))<?= old('physical{{$i}}person2') ?>@else<?= $__env->yieldContent('physical{{$i}}person2')?>@endif">
                </div>
                <div class="form-group col-md-4">
                    <label for="physical{{$i}}due">Due Date</label>
                    <input type="date" class="form-control" id="physical{{$i}}due" name="physical{{$i}}due" value="@if(old('physical{{$i}}due'))<?= old('physical{{$i}}due') ?>@else<?= $__env->yieldContent('physical{{$i}}due')?>@endif">
                </div>
                <input type="hidden" id="physical{{$i}}name" name="physical{{$i}}name" value="90" readonly />
                <?php $i++; ?>
            </div>
          </br>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-danger btn_remove" id="removephysical">Remove Fields</button>
            </div>
          </div>
        </br>
        <h6 style="margin-left: 55px"><b>IFC</b></h6>
          <div style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
                  <label for="physical{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="physical{{$i}}person1" value="@if(old('physical{{$i}}person1'))<?= old('physical{{$i}}person1') ?> @else<?= $__env->yieldContent('physical{{$i}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
              <label for="physical{{$i}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="physical{{$i}}person2" name="physical{{$i}}person2" value="@if(old('physical{{$i}}person2'))<?= old('physical{{$i}}person2') ?>@else<?= $__env->yieldContent('physical{{$i}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="physical{{$i}}due">Due Date</label>
              <input type="date" class="form-control" id="physical{{$i}}due" name="physical{{$i}}due" value="@if(old('physical{{$i}}due'))<?= old('physical{{$i}}due') ?>@else<?= $__env->yieldContent('physical{{$i}}due')?>@endif">
              </div>
              <input type="hidden" id="physical{{$i}}name" name="physical{{$i}}name" value="IFC" readonly />
          </div>
          </br>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-danger btn_remove" id="removephysical">Remove Fields</button>
            </div>
          </div>
          </br>
          <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-warning" id="addphysical">Add New Field</button> 
            </div>
          </div>
          <input type="hidden" id="physicalfields" name="physicalfields" value={{$i}} readonly />
          </br>
          </br>
      @elseif($physicalfields > 0)
      <?php $keycounter = 3; ?>
      <?php for($i = 1; $i <= $physicalfields; $i++){?>
        <?php $keys = array_keys($project['duedates']['physical']);?>
        <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
        <div style="margin-left: 40px" class="row">
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
        </div>
        </br>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="removephysical">Remove Fields</button>
          </div>
        </div>
        </br>
      <?php } ?>
      <input type="hidden" id="physicalfields" name="physicalfields" value={{$physicalfields}} readonly />
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-warning" id="addphysical">Add New Field</button> 
        </div>
      </div>
      @endif


    @if($controlfields >= 0 || !isset($project['duedates']['control']))
    <h5><b> Wiring and Controls Drawing Package</b></h5>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removecontrol">Remove All Control Fields</button>
      </div>
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
    </div>
    </br>
  @endif
  @if(!isset($project['duedates']['control']))
    <?php $i = 1; ?>
    <h6 style="margin-left: 55px"><b>90</b></h6>
        <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
            <label for="control{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="control{{$i}}person1" value="@if(old('control{{$i}}person1'))<?= old('control{{$i}}person1') ?>@else<?= $__env->yieldContent('control{{$i}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="control{{$i}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="control{{$i}}person2" name="control{{$i}}person2" value="@if(old('control{{$i}}person2'))<?= old('control{{$i}}person2') ?>@else<?= $__env->yieldContent('control{{$i}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="control{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="control{{$i}}due" name="control{{$i}}due" value="@if(old('control{{$i}}due'))<?= old('control{{$i}}due') ?>@else<?= $__env->yieldContent('control{{$i}}due')?>@endif">
            </div>
            <input type="hidden" id="control{{$i}}name" name="control{{$i}}name" value="90" readonly />
            <?php $i++; ?>
        </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removecontrol">Remove Fields</button>
        </div>
      </div>
    </br>
    <h6 style="margin-left: 55px"><b>IFC</b></h6>
      <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="control{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" name="control{{$i}}person1" value="@if(old('control{{$i}}person1'))<?= old('control{{$i}}person1') ?> @else<?= $__env->yieldContent('control{{$i}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
          <label for="control{{$i}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="control{{$i}}person2" name="control{{$i}}person2" value="@if(old('control{{$i}}person2'))<?= old('control{{$i}}person2') ?>@else<?= $__env->yieldContent('control{{$i}}person2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="control{{$i}}due">Due Date</label>
          <input type="date" class="form-control" id="control{{$i}}due" name="control{{$i}}due" value="@if(old('control{{$i}}due'))<?= old('control{{$i}}due') ?>@else<?= $__env->yieldContent('control{{$i}}due')?>@endif">
          </div>
          <input type="hidden" id="control{{$i}}name" name="control{{$i}}name" value="IFC" readonly />
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removecontrol">Remove Fields</button>
        </div>
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-warning" id="addcontrol">Add New Field</button> 
        </div>
      </div>
      <input type="hidden" id="controlfields" name="controlfields" value={{$i}} readonly />
      </br>
      </br>
  @elseif($controlfields > 0)
  <?php $keycounter = 3; ?>
  <?php for($i = 1; $i <= $controlfields; $i++){?>
    <?php $keys = array_keys($project['duedates']['control']);?>
    <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
    <div style="margin-left: 40px" class="row">
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
    </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removecontrol">Remove Fields</button>
      </div>
    </div>
    </br>
  <?php } ?>
  <input type="hidden" id="controlfields" name="controlfields" value={{$controlfields}} readonly />
  <div style="margin-left: 40px" class="row">
    <div class="form-group col-md-4">
      <button type="button" class="btn btn-warning" id="addcontrol">Add New Field</button> 
    </div>
  </div>
  @endif


      @if($collectionfields >= 0 || !isset($project['duedates']['collection']))
      <h5><b> Collection Line Drawing Package</b></h5>
      <div class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removecollection">Remove All Collection Fields</button>
        </div>
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
      </div>
      </br>
    @endif
    @if(!isset($project['duedates']['collection']))
      <?php $i = 1; ?>
      <h6 style="margin-left: 55px"><b>90</b></h6>
          <div style="margin-left: 40px" class="row">
              <div class="form-group col-md-4">
              <label for="collection{{$i}}person1">Engineer/Person 1</label>
                  <input type="text" class="form-control" name="collection{{$i}}person1" value="@if(old('collection{{$i}}person1'))<?= old('collection{{$i}}person1') ?>@else<?= $__env->yieldContent('collection{{$i}}person1')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="collection{{$i}}person2">Drafter/Person 2</label>
                  <input type="text" class="form-control" id="collection{{$i}}person2" name="collection{{$i}}person2" value="@if(old('collection{{$i}}person2'))<?= old('collection{{$i}}person2') ?>@else<?= $__env->yieldContent('collection{{$i}}person2')?>@endif">
              </div>
              <div class="form-group col-md-4">
                  <label for="collection{{$i}}due">Due Date</label>
                  <input type="date" class="form-control" id="collection{{$i}}due" name="collection{{$i}}due" value="@if(old('collection{{$i}}due'))<?= old('collection{{$i}}due') ?>@else<?= $__env->yieldContent('collection{{$i}}due')?>@endif">
              </div>
              <input type="hidden" id="collection{{$i}}name" name="collection{{$i}}name" value="90" readonly />
              <?php $i++; ?>
          </div>
        </br>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="removecollection">Remove Fields</button>
          </div>
        </div>
      </br>
      <h6 style="margin-left: 55px"><b>IFC</b></h6>
        <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
                <label for="collection{{$i}}person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="collection{{$i}}person1" value="@if(old('collection{{$i}}person1'))<?= old('collection{{$i}}person1') ?> @else<?= $__env->yieldContent('collection{{$i}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
            <label for="collection{{$i}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="collection{{$i}}person2" name="collection{{$i}}person2" value="@if(old('collection{{$i}}person2'))<?= old('collection{{$i}}person2') ?>@else<?= $__env->yieldContent('collection{{$i}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collection{{$i}}due">Due Date</label>
            <input type="date" class="form-control" id="collection{{$i}}due" name="collection{{$i}}due" value="@if(old('collection{{$i}}due'))<?= old('collection{{$i}}due') ?>@else<?= $__env->yieldContent('collection{{$i}}due')?>@endif">
            </div>
            <input type="hidden" id="collection{{$i}}name" name="collection{{$i}}name" value="IFC" readonly />
        </div>
        </br>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-danger btn_remove" id="removecollection">Remove Fields</button>
          </div>
        </div>
        </br>
        <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-warning" id="addcollection">Add New Field</button> 
          </div>
        </div>
        <input type="hidden" id="collectionfields" name="collectionfields" value={{$i}} readonly />
        </br>
        </br>
    @elseif($collectionfields > 0)
    <?php $keycounter = 3; ?>
    <?php for($i = 1; $i <= $collectionfields; $i++){?>
      <?php $keys = array_keys($project['duedates']['collection']);?>
      <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
      <div style="margin-left: 40px" class="row">
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
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removecollection">Remove Fields</button>
        </div>
      </div>
      </br>
    <?php } ?>
    <input type="hidden" id="collectionfields" name="collectionfields" value={{$collectionfields}} readonly />
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-warning" id="addcollection">Add New Field</button> 
      </div>
    </div>
    @endif



  @if($transmissionfields >= 0 || !isset($project['duedates']['transmission']))
    <h5><b> Transmission Line Drawing Package</b></h5>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removetransmission">Remove All Transmission Fields</button>
      </div>
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
    </div>
    </br>
  @endif
  @if(!isset($project['duedates']['transmission']))
    <?php $i = 1; ?>
    <h6 style="margin-left: 55px"><b>90</b></h6>
        <div style="margin-left: 40px" class="row">
            <div class="form-group col-md-4">
            <label for="transmission{{$i}}person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="transmission{{$i}}person1" value="@if(old('transmission{{$i}}person1'))<?= old('transmission{{$i}}person1') ?>@else<?= $__env->yieldContent('transmission{{$i}}person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission{{$i}}person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="transmission{{$i}}person2" name="transmission{{$i}}person2" value="@if(old('transmission{{$i}}person2'))<?= old('transmission{{$i}}person2') ?>@else<?= $__env->yieldContent('transmission{{$i}}person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission{{$i}}due">Due Date</label>
                <input type="date" class="form-control" id="transmission{{$i}}due" name="transmission{{$i}}due" value="@if(old('transmission{{$i}}due'))<?= old('transmission{{$i}}due') ?>@else<?= $__env->yieldContent('transmission{{$i}}due')?>@endif">
            </div>
            <input type="hidden" id="transmission{{$i}}name" name="transmission{{$i}}name" value="90" readonly />
            <?php $i++; ?>
        </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removetransmission">Remove Fields</button>
        </div>
      </div>
    </br>
    <h6 style="margin-left: 55px"><b>IFC</b></h6>
      <div style="margin-left: 40px" class="row">
          <div class="form-group col-md-4">
              <label for="transmission{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" name="transmission{{$i}}person1" value="@if(old('transmission{{$i}}person1'))<?= old('transmission{{$i}}person1') ?> @else<?= $__env->yieldContent('transmission{{$i}}person1')?>@endif">
          </div>
          <div class="form-group col-md-4">
          <label for="transmission{{$i}}person2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="transmission{{$i}}person2" name="transmission{{$i}}person2" value="@if(old('transmission{{$i}}person2'))<?= old('transmission{{$i}}person2') ?>@else<?= $__env->yieldContent('transmission{{$i}}person2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmission{{$i}}due">Due Date</label>
          <input type="date" class="form-control" id="transmission{{$i}}due" name="transmission{{$i}}due" value="@if(old('transmission{{$i}}due'))<?= old('transmission{{$i}}due') ?>@else<?= $__env->yieldContent('transmission{{$i}}due')?>@endif">
          </div>
          <input type="hidden" id="transmission{{$i}}name" name="transmission{{$i}}name" value="IFC" readonly />
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removetransmission">Remove Fields</button>
        </div>
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-warning" id="addtransmission">Add New Field</button> 
        </div>
      </div>
      <input type="hidden" id="transmissionfields" name="transmissionfields" value={{$i}} readonly />
      </br>
      </br>
  @elseif($transmissionfields > 0)
  <?php $keycounter = 3; ?>
  <?php for($i = 1; $i <= $transmissionfields; $i++){?>
    <?php $keys = array_keys($project['duedates']['transmission']);?>
    <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
    <div style="margin-left: 40px" class="row">
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
    </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove Fields</button>
      </div>
    </div>
    </br>
  <?php } ?>
  <input type="hidden" id="transmissionfields" name="transmissionfields" value={{$transmissionfields}} readonly />
  <div style="margin-left: 40px" class="row">
    <div class="form-group col-md-4">
      <button type="button" class="btn btn-warning" id="addtransmission">Add New Field</button> 
    </div>
  </div>
  @endif


  @if($scadafields >= 0 || !isset($project['duedates']['scada']))
    <h5><b> SCADA </b></h5>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removescada">Remove All SCADA Fields</button>
      </div>
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
    <?php $i = 1; ?>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-warning" id="addscada">Add New Field</button> 
      </div>
      <input type="hidden" id="scadafields" name="scadafields" value={{$i}} readonly />
    </div>
    </br>
    </br>
  @endif
  @if($scadafields > 0)
  <?php $keycounter = 3; ?>
  <?php for($i = 1; $i <= $scadafields; $i++){?>
    <?php $keys = array_keys($project['duedates']['scada']);?>
    <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
    <div style="margin-left: 40px" class="row">
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
    </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removescada">Remove Fields</button>
      </div>
    </div>
    </br>
  <?php } ?>
  <input type="hidden" id="scadafields" name="scadafields" value={{$scadafields}} readonly />
  <div style="margin-left: 40px" class="row">
    <div class="form-group col-md-4">
      <button type="button" class="btn btn-warning" id="addscada">Add New Field</button> 
    </div>
  </div>
  @endif

  <!-- Studies Starts -->

  @if($totalstudies >= 0 || !isset($project['duedates']['studies']))
    <h5><b>Studies</b></h5>
    <div class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove All Studies</button>
      </div>
    </div>
    <h6><b>All Studies</b></h6>
    <div class="row">
      <div class="form-group col-md-4">
          <label for="studiesperson1">Engineer/Person 1</label>
          <input type="text" class="form-control" id="studiesperson1" name="studiesperson1" value="@if(old('studiesperson1'))<?= old('studiesperson1') ?>@else<?= $__env->yieldContent('studiesperson1')?>@endif">
      </div>
      <div class="form-group col-md-4">
          <label for="studiesdue">Due Date</label>
          <input type="date" class="form-control" id="studiesdue" name="studiesdue" value="@if(old('studiesdue'))<?= old('studiesdue') ?>@else<?= $__env->yieldContent('studiesdue')?>@endif">
      </div>
    </div>
    </br>
    </br>
  @endif
  @if(!isset($project['duedates']['studies']))
    <?php $i = 1; ?>
    <h6 style="margin-left: 55px"><b>Reactive Study</b></h6>
    <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
            <label for="study{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="study{{$i}}person1" name="study{{$i}}person1" value="@if(old('study{{$i}}person1'))<?= old('study{{$i}}person1') ?>@else<?= $__env->yieldContent('study{{$i}}person1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="study{{$i}}due">Due Date</label>
            <input type="date" class="form-control" id="study{{$i}}due" name="study{{$i}}due" value="@if(old('study{{$i}}due'))<?= old('study{{$i}}due') ?>@else<?= $__env->yieldContent('study{{$i}}due')?>@endif">
        </div>
        <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value="Reactive Study" readonly />
        <?php $i++ ?>
     </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove Study</button>
      </div>
    </div>
    </br>

    <h6 style="margin-left: 55px"><b>Ampacity Study</b></h6>
    <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
            <label for="study{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="study{{$i}}person1" name="study{{$i}}person1" value="@if(old('study{{$i}}person1'))<?= old('study{{$i}}person1') ?>@else<?= $__env->yieldContent('study{{$i}}person1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="study{{$i}}due">Due Date</label>
            <input type="date" class="form-control" id="study{{$i}}due" name="study{{$i}}due" value="@if(old('study{{$i}}due'))<?= old('study{{$i}}due') ?>@else<?= $__env->yieldContent('study{{$i}}due')?>@endif">
        </div>
        <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value="Ampacity Study" readonly />
        <?php $i++ ?>
     </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove Study</button>
      </div>
    </div>
    </br>

    <h6 style="margin-left: 55px"><b>Load Flow Study</b></h6>
    <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
            <label for="study{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="study{{$i}}person1" name="study{{$i}}person1" value="@if(old('study{{$i}}person1'))<?= old('study{{$i}}person1') ?>@else<?= $__env->yieldContent('study{{$i}}person1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="study{{$i}}due">Due Date</label>
            <input type="date" class="form-control" id="study{{$i}}due" name="study{{$i}}due" value="@if(old('study{{$i}}due'))<?= old('study{{$i}}due') ?>@else<?= $__env->yieldContent('study{{$i}}due')?>@endif">
        </div>
        <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value="Load Flow Study" readonly />
        <?php $i++ ?>
     </div>
    </br>
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudy">Remove Study</button>
      </div>
    </div>
    </br>

    <h6 style="margin-left: 55px"><b>Relay and Coordination Study</b></h6>
    <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
            <label for="study{{$i}}person1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="study{{$i}}person1" name="study{{$i}}person1" value="@if(old('study{{$i}}person1'))<?= old('study{{$i}}person1') ?>@else<?= $__env->yieldContent('study{{$i}}person1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="study{{$i}}due">Due Date</label>
            <input type="date" class="form-control" id="study{{$i}}due" name="study{{$i}}due" value="@if(old('study{{$i}}due'))<?= old('study{{$i}}due') ?>@else<?= $__env->yieldContent('study{{$i}}due')?>@endif">
        </div>
        <input type="hidden" id="study{{$i}}name" name="study{{$i}}name" value="Relay and Coordination Study" readonly />
     </div>
    </br>
     <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove Study</button>
      </div>
    </div>
    </br>
     <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-warning" id="addstudy">Add New Study</button> 
      </div>
    </div>
    <input type="hidden" id="totalstudies" name="totalstudies" value={{$i}} readonly />
    </br>
    </br>

  @elseif ($totalstudies > 0)
    <?php $keycounter = 2; ?>
    <?php for($i = 1; $i <= $totalstudies; $i++){?>
      <?php $keys = array_keys($project['duedates']['studies']);?>
      <h6 style="margin-left: 55px"><b>{{$keys[$keycounter]}}</b></h6>
      <div style="margin-left: 40px" class="row">
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
      </div>
      </br>
      <div style="margin-left: 40px" class="row">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-danger btn_remove" id="removestudies">Remove Study</button>
        </div>
      </div>
      </br>
    <?php } ?>
    <input type="hidden" id="totalstudies" name="totalstudies" value={{$totalstudies}} readonly />
    <div style="margin-left: 40px" class="row">
      <div class="form-group col-md-4">
        <button type="button" class="btn btn-warning" id="addstudy">Add New Study</button> 
      </div>
    </div>
  @endif



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
                        '</div>' +
                        '<input type="hidden" id="row'+ total+'name" name="row'+ total+'name" value="'+ name + '" readonly />' +
                        '<input type="hidden" id="total" name="total" value="'+total+'" readonly />' +
                        '<div class="row">' +
                        '<div class="form-group col-md-4">' +
                          '<button type="button" class="btn btn-danger btn_remove" id="'+total+'">Remove Form</button>'+
                        '</div>' +
                        '</div>';
                $('#dynamic_field').append(field);  
                        } 
});
$("#dynamic_field").on('click', '.btn_remove', function() {
  var button_id = $(this).attr("id");
  $('#row'+button_id+'').remove();
  $('#name'+button_id+'').remove();
  $('#'+button_id+'').remove();
  $('#dynamic_field').append(field);
}); 
}); 

</script>
  </body>
</html> 
