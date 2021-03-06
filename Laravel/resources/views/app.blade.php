<!DOCTYPE html>
@yield('ngApp')
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/"></base>
	<title>Home page - Jetlag</title>
	
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('/css/jetlag.directives.css') }}" rel="stylesheet" type="text/css">
	
	<!-- External CSS files -->
	<link href="{{ asset('/css/leaflet.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/angular-moment-picker.css') }}" rel="stylesheet" type="text/css">

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
	<nav class="jl-navbar" ng-class="{'minimized': appCtrl.isHeaderMinimized}">
		<div class="jl-icon jl-icon-left clickable" ng-show="!appCtrl.leftMenuOpen">
			<i class="fa fa-fw fa-bars"
			ng-click="appCtrl.openLeftMenu()"></i>
		</div>
		
		<div class="jl-icon jl-icon-middle">
			<a href="/home" class="no-decoration">Jetlag</a>
		</div>

		<div class="jl-icon jl-icon-right">
		    <a class="create-article"href="/article/create"><i class="fa fa-fw fa-file-text-o">
		        <i class="fa fa-fw fa-plus"></i>
		    </i></a>
			<span ng-show="!appCtrl.rightMenuOpen">
                <i class="fa fa-fw fa-user clickable"
                ng-click="appCtrl.openRightMenu()"></i>
                @if (Auth::guest())
                <span class="login-register"><a ng-href="@{{'/auth/login?redirectTo='+appCtrl.redirectTo}}">Login</a> / <a href="{{ url('/auth/register') }}">Register</a></span>
                @endif
			</span>
		</div>

	</nav>

	<div class="side-bar-wrapper" ng-show="appCtrl.leftMenuOpen">
		<div class="side-bar side-bar-left">
			<div class="pull-left">
				<i class="fa fa-fw fa-times clickable"
				ng-click="appCtrl.closeLeftMenu()"></i>
			</div>
			<div class="side-bar-content clearfix">
                <div class="social-networks">
                    <h2>Let's keep in touch!</h2>
                    <ul>
                        <li>
                            <a href="https://twitter.com/JetLagFr" class="no-decoration">
                                <img src="{{asset('/images/twitter.png') }}" width=64/>
                            </a>
                        </li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
	<div class="side-bar-wrapper" ng-show="appCtrl.rightMenuOpen">
		<div class="side-bar side-bar-right">
			<div class="pull-right">
				<i class="fa fa-fw fa-times clickable"
				ng-click="appCtrl.closeRightMenu()"></i>
			</div>
            <div class="side-bar-content">
                <div class="user-picture">
                    <img src="{{ asset('/images/user-profile.png') }}" />
                </div>
                <div class="user-profile">
                    @if(Auth::guest())
                    <h2>User</h2>
                    @else
                    <h2><a href="/me" class="no-decoration">{{ Auth::user()->name }}</a></h2>
                    @endif
                </div>
                @if(!Auth::guest())
                <div class="user-subprofile">
                    <a href="{{ url('/auth/logout') }}">Logout</a>
                </div>
                @endif
            </div>
		</div>
	</div>
	<div id="main" class="container-fluid"
	ng-class="{'pushed-right': appCtrl.leftMenuOpen, 'pushed-left': appCtrl.rightMenuOpen}">
	@yield('content')
	</div>

	<!-- Scripts -->
	<script src="{{ asset(elixir('js/thirds.js')) }}"></script>
	<script src="{{ asset('js/jetlag.components.js') }}"></script>
	<script src="{{ asset('js/jetlag.directives.js') }}"></script>
	<script src="{{ asset('/js/jetlag.js') }}"></script>
	@yield('scripts')
</body>
</html>
