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
        @yield('table-header')
          <tbody>
            @yield('table-content')
          </tbody>
        </table>
      </div>
      </div>
    </div>
  </body>
</html> 