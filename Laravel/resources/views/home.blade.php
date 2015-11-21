@extends('app')

@section('content')
<div class="container-fluid homepage">
	<div class="row visible-xs">
		<div class="col-xs-12 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/9.jpg') }})'}">
			<div class="book-overview">
				<div class="line"><i class="fa fa-map-marker"></i>Bombay</div>
				<div class="line"><i class="fa fa-user"></i>Rajesh</div>
				<div class="line"><i class="fa fa-calendar"></i>12/15/2015</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/2.JPG') }})'}">
		</div>
		<div class="hidden-xs col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/3.JPG') }})'}">
		</div>
	</div>
	<div class="row visible-xs">
		<div class="col-xs-6 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/3.JPG') }})'}">
		</div>
		<div class="col-xs-6 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/6.JPG') }})'}">
		
		</div>
	</div>
	<div class="row hidden-xs">
		<div class="col-sm-3 home-thumbnail small">
		3 millions d'utilisateurs !
		</div>
		<div class="col-sm-6 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
		<div class="col-sm-3 home-thumbnail small">
		Rejoins-nous !
		</div>
	</div>
	<div class="row">
		<div class="hidden-xs col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/6.JPG') }})'}">
		
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/7.JPG') }})'}">
		
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/8.JPG') }})'}">
		
		</div>
	</div>
</div>
	
	<!--
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
				@if (Auth::guest())
				<div class="panel-body">
					Why not <a href="{{ url('/auth/login') }}">loggin' in</a> or <a href="{{ url('/auth/register') }}">registering</a> ?
				</div>
				@else
				<div class="panel-body">
					You are logged in!
				</div>
				@endif
			</div>
		</div>
	</div>
	-->
</div>
@endsection
