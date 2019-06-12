<!doctype html>
<html>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
      </br>
      </br>
      @yield('toptool')
      <div class="container">
        @if (\Session::has('success'))
        <div class="alert alert-success">
          <p>{{ \Session::get('success') }}</p>
        </div><br />
        @endif
        <h2><b>@yield('table-title')</b></h2> 
        <table class="table table-striped">
          <thead>
            <tr> 
              @if (count($projects) > 0 & $page == 1)
              <th></th>
              <th></th>
              <th></th>        
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Total</th> 
              @foreach($total_dollars as $each)
                <th>{{ number_format($each, 0, '.', ',') }}</th>
              @endforeach 
            </tr>  
            <tr>
              <th colspan="2">Action</th>
              <th>Project Name</th>
              <th>Dollar Value</th>
              <th colspan="2">Date NTP</th>
              <th colspan="2">Date Energization</th>
              @foreach($months as $month)  
                <th>{{ $month }}</th>
              @endforeach
              @elseif(count($projects) <= 0 & $page == 1)
                <h2>No Won Projects to Display</h2>
              @else
              <th colspan="2">Action</th>
              <th>CEG Proposal Author</th>
              <th>Project Name</th>
              <th>Client Contact</th>
              <th>Client Company</th>
              <th>MW</th>
              <th>Voltage</th> 
              <th>CEG In-house Budget</th>
              <th>Date NTP</th>
              <th>Date Energize</th>   
              <th>Project Status</th>   
              <th>Project Code</th>
              <th>Project Manager</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @yield('content')
          </tbody>
        </table>
      </div>
      </div>
    </div>
  </body>
</html> 