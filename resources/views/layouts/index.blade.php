<!doctype html>
<html>
<link rel="stylesheet" type="text/css" href="/css/app.css">
<style>
.my-custom-scrollbar {
display: block;
height: 200px;
overflow: auto;
width: 50%;
padding-left: 50px;
}
.table-wrapper-scroll-y {
display: block;
}
</style>
  <title id="page-title">@yield('page-title')</title>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
      </br>
      </br>
      
      
      <div class="container">
            @yield('sort') 
            @yield('toptool')
        @if (Session::has('success'))
        <div class="alert alert-success">
          <p>{{ Session::get('success') }}</p>
        </div>
        <br>
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
