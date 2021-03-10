<!doctype html>
<html>
    <!-- Randy Fixes 1/21/2021 to get css and js to load -->
<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet" type="text/css" > 
<script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
<!-- Randy Fixes 1/21/2021 to get css and js to load -->
  <title id="page-title">@yield('page-title')</title>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
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
      <h4>@yield('subtitle')</h4>
      <div class="container">
      </div>
      <?php if(!empty($request)){ ?>
        <div class="row">
          <a href="{{ url('/devindex')}}" class="btn btn-warning">Return to Dev Index</a>
        </div>
      <?php }?>
      <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <?php if(empty($request)){ ?>
            <button type="submit" class="btn btn-success">Submit</button>
            <?php }elseif($request['status'] == "Open"){?>
              <button type="submit" class="btn btn-success">Close</button>
              <input type="hidden" value="close" name="close">
              <?php } ?>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="request_type">Request Type:</label>
            <select class="form-control" id="sel2" <?php echo empty($request) ? '' : 'readonly' ?> name="request_type">  
                @yield('request_type')
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="status">Status:</label>
            <input type="text" class="form-control" name="status" readonly value="<?= $__env->yieldContent('status')?>">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" name="subject" <?php echo empty($request) ? '' : 'readonly' ?> value="@if(old('subject')){{ old('subject') }}@else<?= $__env->yieldContent('subject')?>@endif">
          </div>
          <div class="form-group col-md-4">
            <label for="proposer">Proposer:</label>
            <input type="text" class="form-control" name="proposer" readonly value="<?= $__env->yieldContent('proposer')?>">
          </div>
          <div class="form-group col-md-4">
            <label for="date">Date:</label>
            <input type="text" class="form-control" name="date" readonly value="<?= $__env->yieldContent('date')?>">
          </div>
        </div>
        <div class="form-group">
          <label for="body">Body:</label>
          <textarea style="resize:none" class="form-control" name="body" <?php echo empty($request) ? '' : 'readonly' ?> rows="5">@if(old('body')){{ old('body') }}@else<?= $__env->yieldContent('body')?>@endif</textarea>
        </div>
      </div>
      @yield('image')
  </body>
</html>
