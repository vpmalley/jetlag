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
            <form novalidate>
                <div class="input-group input-group-lg">
                    <span ng-click="homepageCtrl.search()" class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
                    <input class="form-control" type="text" name="searchInput" ng-model="homepageCtrl.searchInput" placeholder="Search...">
                    <input type="submit" ng-show="false" ng-click="homepageCtrl.search()">
                </div>
            </form>
		</div>
	</div>
    <!-- /mobile searchbar -->
    
    <!-- first row of articles -->
	<div class="row hidden-xs">
		<div class="col-sm-4 home-thumbnail big">
		    <jl-article-thumbnail model="homepageCtrl.articleAt(0)" context="homepageCtrl.getContext()" />
		</div>

		<div class="col-sm-4 home-thumbnail big">
		    <jl-article-thumbnail model="homepageCtrl.articleAt(1)" context="homepageCtrl.getContext()" />
		</div>

        <div class="col-sm-4 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(2)" context="homepageCtrl.getContext()" />
		</div>
	</div>
    <!-- /first row of articles -->
    
    <!-- first mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(0)" context="homepageCtrl.getContext()" />
		</div>
        
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(1)" context="homepageCtrl.getContext()" />
		</div>
        
	</div>
    <!-- /first mobile row of articles -->
    
    <!-- searchbar -->
	<div class="row hidden-xs">
		<div class="col-sm-offset-3 col-sm-6 searchbar">
            <form novalidate>
                <div class="input-group input-group-lg">
                    <span ng-if="!homepageCtrl.isSearching" ng-click="homepageCtrl.search()" class="input-group-addon"><i class="fa fa-search fa-fw clickable"></i></span>
                    <span ng-if="homepageCtrl.isSearching" class="input-group-addon"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></span>
                    <input class="form-control" type="text" name="searchInput" ng-disabled="homepageCtrl.isSearching" ng-model="homepageCtrl.searchInput" placeholder="Search...">
                    <input type="submit" ng-show="false" ng-click="homepageCtrl.search()">
                </div>
            </form>
		</div>
	</div>
    <!-- /searchbar -->
    
    <!-- second row of articles -->
    <div class="row hidden-xs">
		
        <div class="col-sm-4 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(3)" context="homepageCtrl.getContext()" />
		</div>
		
        <div class="col-sm-4 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(4)" context="homepageCtrl.getContext()" />
		</div>

        <div class="col-sm-4 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(5)" context="homepageCtrl.getContext()" />
		</div>
        
	</div>
    <!-- /second row of articles -->
    
    <!-- second mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(2)" context="homepageCtrl.getContext()" />
		</div>
        
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(3)" context="homepageCtrl.getContext()" />
		</div>
        
	</div>
    <!-- /second mobile row of articles -->
    
    <!-- third mobile row of articles -->
	<div class="row visible-xs">
    
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(4)" context="homepageCtrl.getContext()" />
		</div>
        
        <div class="col-xs-6 home-thumbnail big">
            <jl-article-thumbnail model="homepageCtrl.articleAt(5)" context="homepageCtrl.getContext()" />
		</div>
        
	</div>
    <!-- /third mobile row of articles -->
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/home.js') }}"></script>
@endsection
