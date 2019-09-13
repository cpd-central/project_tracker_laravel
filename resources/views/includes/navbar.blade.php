<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<style>
  .box { 
    position: relative; 
    top: 10px; 
    width: 25px;
    height: 20px;
  }
  .red {
    background: #f00;
  } 
  .green {
    background: #0f0;
  }
</style>

<div id="app">
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'CEG Project Tracker') }}
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side of Navbar -->
        <ul class="navbar-nav mr-auto">

        </ul>

        <!-- Right Side of Navbar -->        
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->
          @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @if (Route::has('register'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
          @endif
          @else
          <?php 
            $date = new \DateTime(date("Y-m-d H:i:s"), new \DateTimeZone('America/Chicago'));
            $today = new \DateTime(date("Y-m-d H:i:s"), new \DateTimeZone('America/Chicago')); 
            $billing_start_date = $date->modify('first day of next month');
            $time_until_billing = date_diff($billing_start_date, $today)->days;
          ?>
          <li class="nav-item">
            @if ($time_until_billing < 7) 
              <a class="nav-link"><font color="red">Days Until Billing: {{ $time_until_billing }}</font></a>
            @else
              <a class="nav-link">Days Until Billing: {{ $time_until_billing }}</font></a>
            @endif 
          </li>
          <?php $pay_period_sent = \App\Timesheet::where('user', auth()->user()->email)->get()[0]->pay_period_sent ?>      
          <li class="nav-item">
            <a class="nav-link">Timesheet Sent Status: </a>
          </li>
          <li class="nav-item">
            @if ($pay_period_sent)
              <div class="nav-link box green"></div>
            @else
              <div class="nav-link box red"></div>
            @endif
          </li>

          <?php if(auth()->user()->role != "user"){?>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pages.newproject') }}">New Project</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pages.projectindex') }}">Project Index</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pages.wonprojectsummary') }}">Won Project Summary</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pages.hoursgraph') }}">Hours By Project Graph</a>
          </li>
           <?php } ?>
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('home') }}"> Dashboard
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
</div>








