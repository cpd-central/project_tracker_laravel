<!doctype html>
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
              <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit Details</a></td>
         </div>
        </br>
        <h5><b>Physical Drawing Package</b></h5>
        <h6><b>90%</b></h6>
          <div class="row">
            <div class="form-group col-md-4">
                <label for="physicaldrafter90">Drafter</label>
              <input type="text" class="form-control" name="physicaldrafter90" value="@if(old('physicaldrafter90')){{ old('physicaldrafter90') }} @else<?= $__env->yieldContent('physicaldrafter90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicaleng90">Engineer</label>
                <input type="text" class="form-control" id="physicaleng90" name="physicaleng90" value="@if(old('physicaleng90'))<?= old('physicaleng90') ?>@else<?= $__env->yieldContent('physicaleng90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicaldate90">Due Date</label>
                <input type="date" class="form-control" id="physicaldate90" name="physicaldate90" value="@if(old('physicaldate90'))<?= old('physicaldate90') ?>@else<?= $__env->yieldContent('physicaldate90')?>@endif">
            </div>
        </div>         
        <h6><b>IFC</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="physicaldrafterifc">Drafter</label>
              <input type="text" class="form-control" name="physicaldrafterifc" value="@if(old('physicaldrafterifc')){{ old('physicaldrafterifc') }} @else<?= $__env->yieldContent('physicaldrafterifc')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicalengifc">Engineer</label>
                <input type="text" class="form-control" id="physicalengifc" name="physicalengifc" value="@if(old('physicalengifc'))<?= old('physicalengifc') ?>@else<?= $__env->yieldContent('physicalengifc')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="physicaldateifc">Due Date</label>
                <input type="date" class="form-control" id="physicaldateifc" name="physicaldateifc" value="@if(old('physicaldateifc'))<?= old('physicaldateifc') ?>@else<?= $__env->yieldContent('physicaldateifc')?>@endif">
            </div>
         </div>
        </br>
    </br>
    <h5><b>Wiring and Controls Drawing Package</b></h5>
    <h6><b>90%</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="wiredrafter90">Drafter</label>
              <input type="text" class="form-control" name="wiredrafter90" value="@if(old('wiredrafter90')){{ old('wiredrafter90') }} @else<?= $__env->yieldContent('wiredrafter90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wireeng90">Engineer</label>
                <input type="text" class="form-control" id="wireeng90" name="wireeng90" value="@if(old('wireeng90'))<?= old('wireeng90') ?>@else<?= $__env->yieldContent('wireeng90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wiredate90">Due Date</label>
                <input type="date" class="form-control" id="wiredate90" name="wiredate90" value="@if(old('wiredate90'))<?= old('wiredate90') ?>@else<?= $__env->yieldContent('wiredate90')?>@endif">
            </div>
         </div>
    <h6><b>IFC</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="wiredrafterifc">Drafter</label>
              <input type="text" class="form-control" name="wiredrafterifc" value="@if(old('wiredrafterifc')){{ old('wiredrafterifc') }} @else<?= $__env->yieldContent('wiredrafterifc')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wireengifc">Engineer</label>
                <input type="text" class="form-control" id="wireengifc" name="wireengifc" value="@if(old('wireengifc'))<?= old('wireengifc') ?>@else<?= $__env->yieldContent('wireengifc')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="wiredateifc">Due Date</label>
                <input type="date" class="form-control" id="wiredateifc" name="wiredateifc" value="@if(old('wiredateifc'))<?= old('wiredateifc') ?>@else<?= $__env->yieldContent('wiredateifc')?>@endif">
            </div>
         </div>
        </br>
      </br>
    <h5><b> Collection System Drawing Package</b></h5>
    <h6><b>90%</b></h6>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="collectiondrafter90">Drafter</label>
                <input type="text" class="form-control" name="collectiondrafter90" value="@if(old('collectiondrafter90')){{ old('collectiondrafter90') }} @else<?= $__env->yieldContent('collectiondrafter90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collectioneng90">Engineer</label>
                <input type="text" class="form-control" id="collectioneng90" name="collectioneng90" value="@if(old('collectioneng90'))<?= old('collectioneng90') ?>@else<?= $__env->yieldContent('collectioneng90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="collectiondate90">Due Date</label>
                <input type="date" class="form-control" id="collectiondate90" name="collectiondate90" value="@if(old('collectiondate90'))<?= old('collectiondate90') ?>@else<?= $__env->yieldContent('collectiondate90')?>@endif">
            </div>
        </div>
      <h6><b>IFC</b></h6>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="collectiondrafterifc">Drafter</label>
            <input type="text" class="form-control" name="collectiondrafterifc" value="@if(old('collectiondrafterifc')){{ old('collectiondrafterifc') }} @else<?= $__env->yieldContent('collectiondrafterifc')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="collectionengifc">Engineer</label>
              <input type="text" class="form-control" id="collectionengifc" name="collectionengifc" value="@if(old('collectionengifc'))<?= old('collectionengifc') ?>@else<?= $__env->yieldContent('collectionengifc')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="collectiondateifc">Due Date</label>
              <input type="date" class="form-control" id="collectiondateifc" name="collectiondateifc" value="@if(old('collectiondateifc'))<?= old('collectiondateifc') ?>@else<?= $__env->yieldContent('collectiondateifc')?>@endif">
          </div>
      </div>
      </br>
    </br>
    <h5><b> Transmission Line Drawing Package</b></h5>
    <h6><b>90%</b></h6>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="transmissiondrafter90">Drafter</label>
                <input type="text" class="form-control" name="transmissiondrafter90" value="@if(old('transmissiondrafter90')){{ old('transmissiondrafter90') }} @else<?= $__env->yieldContent('transmissiondrafter90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmissioneng90">Engineer</label>
                <input type="text" class="form-control" id="transmissioneng90" name="transmissioneng90" value="@if(old('transmissioneng90'))<?= old('transmissioneng90') ?>@else<?= $__env->yieldContent('transmissioneng90')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="transmissiondate90">Due Date</label>
                <input type="date" class="form-control" id="transmissiondate90" name="transmissiondate90" value="@if(old('transmissiondate90'))<?= old('transmissiondate90') ?>@else<?= $__env->yieldContent('transmissiondate90')?>@endif">
            </div>
        </div>
      <h6><b>IFC</b></h6>
      <div class="row">
          <div class="form-group col-md-4">
              <label for="transmissiondrafterifc">Drafter</label>
            <input type="text" class="form-control" name="transmissiondrafterifc" value="@if(old('transmissiondrafterifc')){{ old('transmissiondrafterifc') }} @else<?= $__env->yieldContent('transmissiondrafterifc')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissionengifc">Engineer</label>
              <input type="text" class="form-control" id="transmissionengifc" name="transmissionengifc" value="@if(old('transmissionengifc'))<?= old('transmissionengifc') ?>@else<?= $__env->yieldContent('transmissionengifc')?>@endif">
          </div>
          <div class="form-group col-md-4">
              <label for="transmissiondateifc">Due Date</label>
              <input type="date" class="form-control" id="transmissiondateifc" name="transmissiondateifc" value="@if(old('transmissiondateifc'))<?= old('transmissiondateifc') ?>@else<?= $__env->yieldContent('transmissiondateifc')?>@endif">
          </div>
      </div>
      </br>
    </br>
    <h5><b>SCADA</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="scadadrafter">Drafter</label>
          <input type="text" class="form-control" name="scadadrafter" value="@if(old('scadadrafter')){{ old('scadadrafter') }} @else<?= $__env->yieldContent('scadadrafter')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="scadaeng">Engineer</label>
            <input type="text" class="form-control" id="scadaeng" name="scadaeng" value="@if(old('scadaeng'))<?= old('scadaeng') ?>@else<?= $__env->yieldContent('scadaeng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="scadadate">Due Date</label>
            <input type="date" class="form-control" id="scadadate" name="scadadate" value="@if(old('scadadate'))<?= old('scadadate') ?>@else<?= $__env->yieldContent('scadadate')?>@endif">
        </div>
     </div>
  </br>
  </br>
    <h5><b>Reactive Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="reactiveeng">Engineer</label>
            <input type="text" class="form-control" id="reactiveeng" name="reactiveeng" value="@if(old('reactiveeng'))<?= old('reactiveeng') ?>@else<?= $__env->yieldContent('reactiveeng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="reactivedate">Due Date</label>
            <input type="date" class="form-control" id="reactivedate" name="reactivedate" value="@if(old('reactivedate'))<?= old('reactivedate') ?>@else<?= $__env->yieldContent('reactivedate')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Ampacity Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="ampacityeng">Engineer</label>
            <input type="text" class="form-control" id="ampacityeng" name="ampacityeng" value="@if(old('ampacityeng'))<?= old('ampacityeng') ?>@else<?= $__env->yieldContent('ampacityeng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="ampacitydate">Due Date</label>
            <input type="date" class="form-control" id="ampacitydate" name="ampacitydate" value="@if(old('ampacitydate'))<?= old('ampacitydate') ?>@else<?= $__env->yieldContent('ampacitydate')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Arc Flash Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="arcflasheng">Engineer</label>
            <input type="text" class="form-control" id="arcflasheng" name="arcflasheng" value="@if(old('arcflasheng'))<?= old('arcflasheng') ?>@else<?= $__env->yieldContent('arcflasheng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="arcflashdate">Due Date</label>
            <input type="date" class="form-control" id="arcflashdate" name="arcflashdate" value="@if(old('arcflashdate'))<?= old('arcflashdate') ?>@else<?= $__env->yieldContent('arcflashdate')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>Relay and Coordination Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="relayeng">Engineer</label>
            <input type="text" class="form-control" id="relayeng" name="relayeng" value="@if(old('relayeng'))<?= old('relayeng') ?>@else<?= $__env->yieldContent('relayeng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="relaydate">Due Date</label>
            <input type="date" class="form-control" id="relaydate" name="relaydate" value="@if(old('relaydate'))<?= old('relaydate') ?>@else<?= $__env->yieldContent('relaydate')?>@endif">
        </div>
     </div>
    </br>
    </br>
    <h5><b>All Others Study</b></h5>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="alleng">Engineer</label>
            <input type="text" class="form-control" id="alleng" name="alleng" value="@if(old('alleng'))<?= old('alleng') ?>@else<?= $__env->yieldContent('alleng')?>@endif">
        </div>
        <div class="form-group col-md-4">
            <label for="alldate">Due Date</label>
            <input type="date" class="form-control" id="alldate" name="alldate" value="@if(old('alldate'))<?= old('alldate') ?>@else<?= $__env->yieldContent('alldate')?>@endif">
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
  </body>
</html> 
