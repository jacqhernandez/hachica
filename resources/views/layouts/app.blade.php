<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Hachica Store') }}</title>
  
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font.css') }}" rel='stylesheet'>
  <link href="{{ asset('css/font-awesome.css') }}" rel='stylesheet'>
  <link href="{{ asset('css/jquery-ui.css') }}" rel='stylesheet'>
  <link href="{{ asset('css/jquery-ui.min.css') }}" rel='stylesheet'>
 	<style>
    html, body {
	    font-family: 'Roboto', sans-serif;
	    font-weight: 100;
	    font-size: 14px;
    }
    .navbar-brand, .nav.navbar-nav.navbar-right{
    	font-family: 'Raleway', sans-serif;
    	font-weight: 300;
    }
    .panel-heading{
    	font-family: 'Raleway', sans-serif;
    	font-weight: 300;
    }
  </style>

</head>
<body>
  <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">

          <!-- Collapsed Hamburger -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- Branding Image -->
          <a class="navbar-brand" href="{{ url('/') }}">
              {{ config('app.name', 'Hachica Store') }}
          </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
              &nbsp;
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @guest
              <li><a href="{{ route('login') }}">Login</a></li>
              <li><a href="{{ route('register') }}">Register</a></li>
            @else
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                  <ul class="dropdown-menu" style="text-align: right; min-width: 140px;">
                  	<li><a href="{{ route('index') }}">Dashboard</a></li>
                    <li><a href="{{ route('items.index') }}">Items</a></li>
                    <li><a href="{{ route('sales.index') }}">Sales</a></li>
                    <li><a href="{{ route('sales.create') }}">Create Sale</a></li>
                    <li role="presentation" class="divider"></li>
                    <li>
                      <a href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                          Logout
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                    </li>
                  </ul>
              </li>
              @endguest
            </ul>
        </div>
       <div>
     </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            @if(Session::has('success'))
		  				<div class="alert alert-success alert-dismissible fade in" role="alert" style="font-family: 'Raleway', sans-serif; font-weight: 300;">
			  				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    				<span aria-hidden="true">&times;</span>
			  				</button>
			  				{{Session::get('success')}}
							</div>
						@endif
            @yield('content')

             
       
    


        </div>
    </div>
</div>
</div>
</div>

<!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/jquery-ui.js') }}"></script>
  <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('js/sorttable.js') }}"></script>
  <script src="{{ asset('js/moment.js') }}"></script>
  <script src="{{ asset('js/chart.js') }}"></script>
 
  @yield('script')

 
</body>
</html>
