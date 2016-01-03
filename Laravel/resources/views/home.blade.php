@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.home">
@endsection

@section('head')
<link href="{{ asset('/css/home.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="homepage" ng-controller="HomepageController as homepageCtrl">
	<div class="row visible-xs">
		<div class="col-xs-12 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/9.jpg') }})'}">
			<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>Bombay</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Rajesh</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2015</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/2.JPG') }})'}">
			<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
		<div class="hidden-xs col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/3.JPG') }})'}">
			<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row visible-xs">
		<div class="col-xs-6 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/3.JPG') }})'}">
		<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/6.JPG') }})'}">
		<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row hidden-xs">
		<div class="col-sm-offset-3 col-sm-6 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="hidden-xs col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/6.JPG') }})'}">
		<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/7.JPG') }})'}">
		<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>L'Estartit</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Erica</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2012</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': 'url({{ asset('/images/8.JPG') }})'}">
		<div class="book-overview">
				<div class="strut"></div>
				<div class="book-overview-content clickable">
					<div class="line"><i class="fa fa-fw fa-map-marker"></i>Sahara</div>
					<div class="line"><i class="fa fa-fw fa-user"></i>Jamel</div>
					<div class="line"><i class="fa fa-fw fa-calendar"></i>12/15/2015</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="//localhost/jetlag/Laravel/public/js/home.js"></script>
@endsection
