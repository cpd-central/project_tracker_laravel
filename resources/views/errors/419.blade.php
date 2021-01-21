<!DOCTYPE html>
<title>Error 419</title>
@include('includes.navbar')
<!-- Randy Fixes 1/21/2021 to get css and js to load -->
<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet" type="text/css" > 
<script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
<!-- Randy Fixes 1/21/2021 to get css and js to load -->
<body>
  <div class="container"> 
    <div class="flex-center position-ref full-height"> 
      <div style="text-align: center" class="message" style="padding: 10px;">
        <br>
        <h3>419</h3>
        </br>
        <h4>Page Expired</h4>
        </br>
        This is likely due to a double click on a project submission or deletion.
        </br>
        To ensure your data is as you intend, please redirect to the <a href={{ route('home') }}>Dashboard</a>.
        </br>
        If you believe you are seeing this page in error, please contact Randall Clintsman or Stephen Peichel.
      </div>
    </div>
  </div>
</body>
</html>










