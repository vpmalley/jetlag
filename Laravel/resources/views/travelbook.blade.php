@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbook">
@endsection

@section('head')
<link href="{{ asset('/css/travelbook.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="travelbook" ng-controller="TravelbookController as travelbookCtrl">
	<div class="travelbook-header">
	  <div class="header-top" style="background-image:url(@{{'/images/' + travelbookCtrl.travelbook.descriptionPicture.url}})">
	    <div class="header-top-content">
	      <h1>@{{travelbookCtrl.travelbook.title}}</h1>
		  <p class="description">@{{travelbookCtrl.travelbook.descriptionText}}</p>
		  <div class="space-and-time">
		    <span><i class="fa fa-fw fa-map-marker"></i> @{{travelbookCtrl.travelbook.location.label}}</span>
		    <span><i class="fa fa-fw fa-calendar-o"></i> @{{travelbookCtrl.travelbook.begin_date | momentToString}}</span>
		  </div>
		  <div class="jl-btn jl-btn-empty jl-btn-big fullscreen">FULL</div>
		  <div class="social">
			<div class="jl-btn-group">
			  <div class="jl-btn jl-btn-empty jl-btn-big">
			    <i class="fa fa-fw fa-lg fa-facebook"></i>
			  </div>
			  <div class="jl-btn jl-btn-empty jl-btn-big">
			    <i class="fa fa-fw fa-lg fa-twitter"></i>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="header-bottom">
	    <div class="jl-btn jl-btn-empty jl-btn-big">EDIT</div>
		<div class="authors"><span>Val LeNain</span>, <span>Thierry</span>, <span>Belette</span>, <span>Skread</span> <span class="more">and more</span></div>
		<div class="dates">Publié le 25.04.2016. Dernière modification le 28.04.2016.</div>
	  </div>
	</div>
	<div class="travelbook-content-table">
        <div class="content-entry" ng-repeat="article in travelbookCtrl.travelbook.articles">
            <div class="content-entry-inner clickable" ng-click="travelbookCtrl.navigateTo(article)">
                <h2 class="entry-title">@{{article.title}}</h2>
                <div class="entry-details">
                    <div><i class="fa fa-fw fa-map-marker"></i> @{{article.location.label}}</div>
                    <div><i class="fa fa-fw fa-calendar-o"></i> @{{article.date | momentToString}}</div>
                </div>
             </div>
        </div>
	</div>
	<div class="travelbook-article" ng-if="travelbookCtrl.currentArticle !== null">
		<div class="article-nav-buttons">
			<div class="jl-btn-group">
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasPreviousArticle()" ng-click="travelbookCtrl.navigateToPrevious()">Précédent</div>
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasNextArticle()" ng-click="travelbookCtrl.navigateToNext()">Suivant</div>
			</div>
		</div>
		<div class="article-header">
			<h2 class="article-title"></h2>
			<div class="article-details">
				<span><i class="fa fa-fw fa-map-marker"></i> @{{travelbookCtrl.currentArticle.location.label}}</span>
				<span><i class="fa fa-fw fa-calendar-o"></i> @{{travelbookCtrl.currentArticle.date | momentToString}}</span>
			</div>
		</div>
		<div class="article">
		    <jl-paragraph ng-repeat="paragraph in travelbookCtrl.currentArticle.paragraphs" model="paragraph" class="paragraph"></jl-paragraph>
		</div>
		<div class="article-nav-buttons">
			<div class="jl-btn-group">
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasPreviousArticle()" ng-click="travelbookCtrl.navigateToPrevious()">Précédent</div>
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasNextArticle()" ng-click="travelbookCtrl.navigateToNext()">Suivant</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/travelbook.js') }}"></script>
@endsection