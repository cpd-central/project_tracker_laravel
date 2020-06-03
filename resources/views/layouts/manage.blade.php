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
        <td><a href="{{action('ProjectController@planner')}}" class="btn btn-warning">Back to Project Planner</a></td>
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
        <h5><b>Physical Drawing Package 90%</b></h5>
          <div class="row">
            <div class="form-group col-md-4">
                <label for="physical90person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="physical90person1" value="@if(old('physical90person1')){{ old('physical90person1') }} @else<?= $__env->yieldContent('physical90person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physical90person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="physical90person2" name="physical90person2" value="@if(old('physical90person2'))<?= old('physical90person2') ?>@else<?= $__env->yieldContent('physical90person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physical90due">Due Date</label>
                <input type="date" class="form-control" id="physical90due" name="physical90due" value="@if(old('physical90due'))<?= old('physical90due') ?>@else<?= $__env->yieldContent('physical90due')?>@endif">
            </div>
        </div> 
      </br>
    </br>        
        <h5><b>Physical Drawing Package IFC</b></h5>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="physicalifcperson1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="physicalifcperson1" value="@if(old('physicalifcperson1')){{ old('physicalifcperson1') }} @else<?= $__env->yieldContent('physicalifcperson1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicalifcperson2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="physicalifcperson2" name="physicalifcperson2" value="@if(old('physicalifcperson2'))<?= old('physicalifcperson2') ?>@else<?= $__env->yieldContent('physicalifcperson2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicalifcdue">Due Date</label>
                <input type="date" class="form-control" id="physicalifcdue" name="physicalifcdue" value="@if(old('physicalifcdue'))<?= old('physicalifcdue') ?>@else<?= $__env->yieldContent('physicalifcdue')?>@endif">
            </div>
         </div>
        </br>
    </br>
    <h5><b>Wiring and Controls Drawing Package 90%</b></h5>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="wire90person1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="wire90person1" value="@if(old('wire90person1')){{ old('wire90person1') }} @else<?= $__env->yieldContent('wire90person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wire90person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="wire90person2" name="wire90person2" value="@if(old('wire90person2'))<?= old('wire90person2') ?>@else<?= $__env->yieldContent('wire90person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wire90due">Due Date</label>
                <input type="date" class="form-control" id="wire90due" name="wire90due" value="@if(old('wire90due'))<?= old('wire90due') ?>@else<?= $__env->yieldContent('wire90due')?>@endif">
            </div>
         </div>
        </br>
      </br>
    <h5><b>Wiring and Controls Drawing Package IFC</b></h5>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="wireifcperson1">Engineer/Person 1</label>
              <input type="text" class="form-control" name="wireifcperson1" value="@if(old('wireifcperson1')){{ old('wireifcperson1') }} @else<?= $__env->yieldContent('wireifcperson1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wireifcperson2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="wireifcperson2" name="wireifcperson2" value="@if(old('wireifcperson2'))<?= old('wireifcperson2') ?>@else<?= $__env->yieldContent('wireifcperson2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wireifcdue">Due Date</label>
                <input type="date" class="form-control" id="wireifcdue" name="wireifcdue" value="@if(old('wireifcdue'))<?= old('wireifcdue') ?>@else<?= $__env->yieldContent('wireifcdue')?>@endif">
            </div>
         </div>
        </br>
      </br>
    <h5><b> Collection System Drawing Package 90%</b></h5>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="collection90person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="collection90person1" value="@if(old('collection90person1')){{ old('collection90person1') }} @else<?= $__env->yieldContent('collection90person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collection90person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="collection90person2" name="collection90person2" value="@if(old('collection90person2'))<?= old('collection90person2') ?>@else<?= $__env->yieldContent('collection90person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collection90due">Due Date</label>
                <input type="date" class="form-control" id="collection90due" name="collection90due" value="@if(old('collection90due'))<?= old('collection90due') ?>@else<?= $__env->yieldContent('collection90due')?>@endif">
            </div>
        </div>
      </br>
    </br>
    <h5><b> Collection System Drawing Package IFC</b></h5>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="collectionifcperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" name="collectionifcperson1" value="@if(old('collectionifcperson1')){{ old('collectionifcperson1') }} @else<?= $__env->yieldContent('collectionifcperson1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="collectionifcperson2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="collectionifcperson2" name="collectionifcperson2" value="@if(old('collectionifcperson2'))<?= old('collectionifcperson2') ?>@else<?= $__env->yieldContent('collectionifcperson2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="collectionifcdue">Due Date</label>
              <input type="date" class="form-control" id="collectionifcdue" name="collectionifcdue" value="@if(old('collectionifcdue'))<?= old('collectionifcdue') ?>@else<?= $__env->yieldContent('collectionifcdue')?>@endif">
          </div>
      </div>
      </br>
    </br>
    <h5><b> Transmission Line Drawing Package 90%</b></h5>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="transmission90person1">Engineer/Person 1</label>
                <input type="text" class="form-control" name="transmission90person1" value="@if(old('transmission90person1')){{ old('transmission90person1') }} @else<?= $__env->yieldContent('transmission90person1')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission90person2">Drafter/Person 2</label>
                <input type="text" class="form-control" id="transmission90person2" name="transmission90person2" value="@if(old('transmission90person2'))<?= old('transmission90person2') ?>@else<?= $__env->yieldContent('transmission90person2')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmission90due">Due Date</label>
                <input type="date" class="form-control" id="transmission90due" name="transmission90due" value="@if(old('transmission90due'))<?= old('transmission90due') ?>@else<?= $__env->yieldContent('transmission90due')?>@endif">
            </div>
        </div>
      </br>
    </br>
    <h5><b> Transmission Line Drawing Package IFC</b></h5>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="transmissionifcperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" name="transmissionifcperson1" value="@if(old('transmissionifcperson1')){{ old('transmissionifcperson1') }} @else<?= $__env->yieldContent('transmissionifcperson1')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissionifcperson2">Drafter/Person 2</label>
              <input type="text" class="form-control" id="transmissionifcperson2" name="transmissionifcperson2" value="@if(old('transmissionifcperson2'))<?= old('transmissionifcperson2') ?>@else<?= $__env->yieldContent('transmissionifcperson2')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissionifcdue">Due Date</label>
              <input type="date" class="form-control" id="transmissionifcdue" name="transmissionifcdue" value="@if(old('transmissionifcdue'))<?= old('transmissionifcdue') ?>@else<?= $__env->yieldContent('transmissionifcdue')?>@endif">
          </div>
      </div>
      </br>
    </br>
    <h5><b>SCADA</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="scadaperson1">Engineer/Person 1r</label>
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
  </br>
  </br>
    <h5><b>Reactive Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="reactiveperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="reactiveperson1" name="reactiveperson1" value="@if(old('reactiveperson1'))<?= old('reactiveperson1') ?>@else<?= $__env->yieldContent('reactiveperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="reactivedue">Due Date</label>
            <input type="date" class="form-control" id="reactivedue" name="reactivedue" value="@if(old('reactivedue'))<?= old('reactivedue') ?>@else<?= $__env->yieldContent('reactivedue')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Ampacity Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="ampacityperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="ampacityperson1" name="ampacityperson1" value="@if(old('ampacityperson1'))<?= old('ampacityperson1') ?>@else<?= $__env->yieldContent('ampacityperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="ampacitydue">Due Date</label>
            <input type="date" class="form-control" id="ampacitydue" name="ampacitydue" value="@if(old('ampacitydue'))<?= old('ampacitydue') ?>@else<?= $__env->yieldContent('ampacitydue')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Arc Flash Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="arcflashperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="arcflashperson1" name="arcflashperson1" value="@if(old('arcflashperson1'))<?= old('arcflashperson1') ?>@else<?= $__env->yieldContent('arcflashperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="arcflashdue">Due Date</label>
            <input type="date" class="form-control" id="arcflashdue" name="arcflashdue" value="@if(old('arcflashdue'))<?= old('arcflashdue') ?>@else<?= $__env->yieldContent('arcflashdue')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Relay and Coordination Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="relayperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="relayperson1" name="relayperson1" value="@if(old('relayperson1'))<?= old('relayperson1') ?>@else<?= $__env->yieldContent('relayperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="relaydue">Due Date</label>
            <input type="date" class="form-control" id="relaydue" name="relaydue" value="@if(old('relaydue'))<?= old('relaydue') ?>@else<?= $__env->yieldContent('relaydue')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>All Others Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="allperson1">Engineer/Person 1</label>
            <input type="text" class="form-control" id="allperson1" name="allperson1" value="@if(old('allperson1'))<?= old('allperson1') ?>@else<?= $__env->yieldContent('allperson1')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="alldue">Due Date</label>
            <input type="date" class="form-control" id="alldue" name="alldue" value="@if(old('alldue'))<?= old('alldue') ?>@else<?= $__env->yieldContent('alldue')?>@endif">
        </div>
     </div>
    </br>
    <div id="dynamic_field">
    </div>
    </br>
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
  <script type="text/javascript" src="{{ URL::asset('js/addfields.js')}}"></script>
  </body>
</html> 
