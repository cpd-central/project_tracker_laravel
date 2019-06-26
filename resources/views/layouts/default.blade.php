<!doctype html>
<html>
  <title id="page-title">@yield('page-title')</title>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
        @yield('content')
      </div>
  </body>
</html> 



