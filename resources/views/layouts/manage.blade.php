<!doctype html>
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
         </div>
        </br>
        <h5><b>Physical Drawing Package</b></h5>
        <h6><b>90%</b></h6>
          <div class="row">
            <div class="form-group col-md-4">
                <label for="90physicaldrafter">Drafter</label>
              <input type="text" class="form-control" name="90physicaldrafter" value="@if(old('90physicaldrafter')){{ old('90physicaldrafter') }} @else<?= $__env->yieldContent('90physicaldrafter')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="90physicaleng">Engineer</label>
                <input type="text" class="form-control" id="90physicaleng" name="90physicaleng" value="@if(old('90physicaleng'))<?= old('90physicaleng') ?>@else<?= $__env->yieldContent('90physicaleng')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="90physicaldate">Due Date</label>
                <input type="date" class="form-control" id="90physicaldate" name="90physicaldate" value="@if(old('90physicaldate'))<?= old('90physicaldate') ?>@else<?= $__env->yieldContent('90physicaldate')?>@endif">
            </div>
        </div>         
        <h6><b>IFC</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="ifcphysicaldrafter">Drafter</label>
              <input type="text" class="form-control" name="ifcphysicaldrafter" value="@if(old('ifcphysicaldrafter')){{ old('ifcphysicaldrafter') }} @else<?= $__env->yieldContent('ifcphysicaldrafter')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="ifcphysicaleng">Engineer</label>
                <input type="text" class="form-control" id="ifcphysicaleng" name="ifcphysicaleng" value="@if(old('ifcphysicaleng'))<?= old('ifcphysicaleng') ?>@else<?= $__env->yieldContent('ifcphysicaleng')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="ifcphysicaldate">Due Date</label>
                <input type="date" class="form-control" id="ifcphysicaldate" name="ifcphysicaldate" value="@if(old('ifcphysicaldate'))<?= old('ifcphysicaldate') ?>@else<?= $__env->yieldContent('ifcphysicaldate')?>@endif">
            </div>
         </div>
        </br>
    </br>
    <h5><b>Wiring and Controls Drawing Package</b></h5>
    <h6><b>90%</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="90wiredrafter">Drafter</label>
              <input type="text" class="form-control" name="90wiredrafter" value="@if(old('90wiredrafter')){{ old('90wiredrafter') }} @else<?= $__env->yieldContent('90wiredrafter')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="90wireeng">Engineer</label>
                <input type="text" class="form-control" id="90wireeng" name="90wireeng" value="@if(old('90wireeng'))<?= old('90wireeng') ?>@else<?= $__env->yieldContent('90wireeng')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="90wiredate">Due Date</label>
                <input type="date" class="form-control" id="90wiredate" name="90wiredate" value="@if(old('90wiredate'))<?= old('90wiredate') ?>@else<?= $__env->yieldContent('90wiredate')?>@endif">
            </div>
         </div>
    <h6><b>IFC</b></h6>
         <div class="row">
            <div class="form-group col-md-4">
                <label for="ifcwiredrafter">Drafter</label>
              <input type="text" class="form-control" name="ifcwiredrafter" value="@if(old('ifcwiredrafter')){{ old('ifcwiredrafter') }} @else<?= $__env->yieldContent('ifcwiredrafter')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="ifcwireeng">Engineer</label>
                <input type="text" class="form-control" id="ifcwireeng" name="ifcwireeng" value="@if(old('ifcwireeng'))<?= old('ifcwireeng') ?>@else<?= $__env->yieldContent('ifcwireeng')?>@endif">
            </div>
            <div class="form-group col-md-4">
                <label for="ifcwiredate">Due Date</label>
                <input type="date" class="form-control" id="ifcwiredate" name="ifcwiredate" value="@if(old('ifcwiredate'))<?= old('ifcwiredate') ?>@else<?= $__env->yieldContent('ifcwiredate')?>@endif">
            </div>
         </div>
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
    </div>
  </body>
</html> 
