<!DOCTYPE html>
@yield('ngApp')
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home page - Jetlag</title>
	
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('/css/jetlag.directives.css') }}" rel="stylesheet" type="text/css">
	
	<!-- External CSS files -->
	<link href="{{ asset('/css/leaflet.css') }}" rel="stylesheet" type="text/css">

	<!-- Fonts -->
	<link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
	<link href="//fonts.googleapis.com/css?family=Roboto:400,300" rel="stylesheet" type="text/css">
	
	<!--- Page specific files --->
	@yield('head')

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body ng-controller="AppController as appCtrl" class="jetlag-app">
	<nav class="jl-navbar">
		<div class="jl-icon jl-icon-left clickable" ng-show="!appCtrl.leftMenuOpen">
			<i class="fa fa-fw fa-bars"
			ng-click="appCtrl.openLeftMenu()"></i>
		</div>
		
		<div class="jl-icon jl-icon-middle">
			Jetlag
		</div>
		
		<div class="jl-icon jl-icon-right clickable" ng-show="!appCtrl.rightMenuOpen">
			<i class="fa fa-fw fa-user"
			ng-click="appCtrl.openRightMenu()"></i>
			@if (Auth::guest())
			<span><a ng-href="@{{'/auth/login?redirectTo='+appCtrl.redirectTo}}">Login</a> / <a href="{{ url('/auth/register') }}">Register</a></span>
			@endif
		</div>

	</nav>

	<div class="side-bar-wrapper" ng-show="appCtrl.leftMenuOpen">
		<div class="side-bar side-bar-left">
			<div class="pull-right">
				<i class="fa fa-fw fa-times clickable"
				ng-click="appCtrl.closeLeftMenu()"></i>
			</div>
			<h2>Menu</h2>
			<div class="side-bar-content clearfix">
			Something
			</div>
		</div>
	</div>
	<div class="side-bar-wrapper" ng-show="appCtrl.rightMenuOpen">
		<div class="side-bar side-bar-right">
			<div class="pull-right">
				<i class="fa fa-fw fa-times clickable"
				ng-click="appCtrl.closeRightMenu()"></i>
			</div>
			@if(Auth::guest())
			<h2>User</h2>
			@else
			<h2>{{ Auth::user()->name }}</h2>
			@endif
			<div class="side-bar-content clearfix">
			<ul>
			@if(!Auth::guest())
			<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
			@endif
			</ul>
			</div>
		</div>
	</div>
	<div id="main" class="container-fluid"
	ng-class="{'pushed-right': appCtrl.leftMenuOpen, 'pushed-left': appCtrl.rightMenuOpen}">
	@yield('content')
	</div>

	<!-- Scripts -->
	<script src="{{ asset(elixir('js/thirds.js')) }}"></script>
	<script src="{{ asset('js/jetlag.directives.js') }}"></script>
	<script src="{{ asset('/js/jetlag.js') }}"></script>
	@yield('scripts')
</body>
</html>
