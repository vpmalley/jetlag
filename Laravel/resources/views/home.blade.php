@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.home">
@endsection

@section('head')
<link href="{{ asset('/css/home.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="homepage" ng-controller="HomepageController as homepageCtrl">
    <!-- mobile searchbar -->
	<div class="row visible-xs">
		<div class="col-xs-12 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
	</div>
    <!-- /mobile searchbar -->
    <!-- first row of articles -->
	<div class="row">
		<div class="col-xs-6 col-sm-4 home-thumbnail big"
		ng-style="{'background-image': homepageCtrl.articles.at(0) != null ? 'url('+homepageCtrl.articles.at(0).$attributes.descriptionMedia.smallUrl+')' : '/images/generic.png'}">
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
		ng-style="{'background-image': homepageCtrl.articles.at(1) != null ? 'url('+homepageCtrl.articles.at(1).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
        ng-style="{'background-image': homepageCtrl.articles.at(2) != null ? 'url('+homepageCtrl.articles.at(2).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
    <!-- /first row of articles -->
    <!-- first mobile row of articles -->
	<div class="row visible-xs">
		<div class="col-xs-6 home-thumbnail big"
		ng-style="{'background-image': homepageCtrl.articles.at(3) != null ? 'url('+homepageCtrl.articles.at(3).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
		ng-style="{'background-image': homepageCtrl.articles.at(4) != null ? 'url('+homepageCtrl.articles.at(4).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
    <!-- /first mobile row of articles -->
    <!-- searchbar -->
	<div class="row hidden-xs">
		<div class="col-sm-offset-3 col-sm-6 searchbar">
			<div class="input-group input-group-lg">
				<span class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
				<input class="form-control" type="text" placeholder="Search...">
			</div>
		</div>
	</div>
    <!-- /searchbar -->
    <!-- second row of articles -->
	<div class="row">
		<div class="hidden-xs col-sm-4 home-thumbnail big"
		ng-style="{'background-image': homepageCtrl.articles.at(5) != null ? 'url('+homepageCtrl.articles.at(5).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
		ng-style="{'background-image': homepageCtrl.articles.at(6) != null ? 'url('+homepageCtrl.articles.at(6).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
		ng-style="{'background-image': homepageCtrl.articles.at(7) != null ? 'url('+homepageCtrl.articles.at(7).$attributes.descriptionMedia.smallUrl+')' : 'url(/images/generic.png)'}">
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
<script src="{{ asset('/js/home.js') }}"></script>
@endsection
