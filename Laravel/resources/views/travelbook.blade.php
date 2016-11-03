@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbook">
@endsection

@section('head')
<link href="{{ asset('/css/travelbook.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="travelbook" ng-class="{'edit': travelbookCtrl.mode === 'edit'}" ng-controller="TravelbookController as travelbookCtrl">
	<div class="travelbook-header">
	  <div class="header-top" style="background-image:url(@{{'/images/' + travelbookCtrl.travelbook.descriptionPicture.url}})">
	    <div class="header-top-content">
          <div jl-in-context-editable active="travelbookCtrl.isBeingEdited()" cancel-edit="travelbookCtrl.toggleActive()" valid-edit="travelbookCtrl.toggleActive()">
            <h1 class="header-top-content-title">
                <span class="editing-hidden">@{{travelbookCtrl.travelbook.title}}</span>
                <input class="editing-visible" type="text" name="travelbook-title" ng-model="travelbookCtrl.travelbook.title" />      
            </h1>
          </div>
          <div jl-in-context-editable active="travelbookCtrl.isBeingEdited()" cancel-edit="travelbookCtrl.toggleActive()" valid-edit="travelbookCtrl.toggleActive()">
            <p class="description">
                <span class="editing-hidden">@{{travelbookCtrl.travelbook.descriptionText}}</span>
                <textarea class="editing-visible" style="display:block;width:100%" name="travelbook-description" ng-model="travelbookCtrl.travelbook.descriptionText"></textarea>
            </p>      
          </div>
		  <div class="space-and-time">
		    <span jl-in-context-editable active="travelbookCtrl.isBeingEdited()" cancel-edit="travelbookCtrl.toggleActive()" valid-edit="travelbookCtrl.toggleActive()">
                <span class="editing-hidden"><i class="fa fa-fw fa-map-marker"></i> @{{travelbookCtrl.travelbook.location.label}}</span>
                <span class="editing-visible"><i class="fa fa-fw fa-map-marker"></i> <input type="text" name="travelbook-location" ng-model="travelbookCtrl.travelbook.location.label" /></span>
            </span>
		    <span jl-in-context-editable active="travelbookCtrl.isBeingEdited()" cancel-edit="travelbookCtrl.toggleActive()" valid-edit="travelbookCtrl.toggleActive()">
                <span class="editing-hidden"><i class="fa fa-fw fa-calendar-o"> </i>@{{travelbookCtrl.travelbook.begin_date | momentToString}}</span>
                <span class="editing-visible"><i class="fa fa-fw fa-calendar-o"> </i>
                    <input ng-model="travelbookCtrl.travelbook.begin_date" 
                        moment-picker="travelbookCtrl.travelbook.begin_date"
                        format="DD.MM.YYYY"
                        min-view="year"
                        max-view="day"
                        start-view="day"
                        autoclose="true">
                </span>
            </span>
          </div>
		  <div class="jl-btn jl-btn-empty jl-btn-big fullscreen" style="display:none">FULL</div>
		  <div class="social" style="display:none">
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
	    <div class="jl-btn jl-btn-empty jl-btn-big" ng-click="travelbookCtrl.editTravelbook()">
            <span ng-if="travelbookCtrl.mode === 'display'">EDIT</span>
            <span ng-if="travelbookCtrl.mode === 'edit'">DONE EDITING</span>
        </div>
		<div class="authors"><span><a href="/me">Val LeNain</a></span>, <span>Thierry</span>, <span>Belette</span>, <span>Skread</span> <span class="more">and more</span></div>
		<div class="dates">Publié le 25.04.2016. Dernière modification le 28.04.2016.</div>
	  </div>
	</div>
    
    <!-- Travelbook content table with the different articles -->
	<div class="travelbook-content-table">
        <div class="content-entry create-article" ng-if="travelbookCtrl.mode === 'edit'">
            <div class="content-entry-inner clickable" ng-click="travelbookCtrl.createNewArticle()">
                <span class="strut"></span><!--
                --><span><i class="fa fa-fw fa-plus"> </i>write new story</span>
             </div>
        </div>
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
    
    <!-- Display of the selected article if any -->
	<div class="travelbook-article" ng-if="travelbookCtrl.currentArticle !== null">
		<div class="article-nav-buttons">
			<div class="jl-btn-group">
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasPreviousArticle()" ng-click="travelbookCtrl.navigateToPrevious()">Précédent</div>
				<div class="jl-btn jl-btn-empty" ng-if="travelbookCtrl.hasNextArticle()" ng-click="travelbookCtrl.navigateToNext()">Suivant</div>
			</div>
		</div>
		<div class="article-header">
			<h2 class="article-title">@{{travelbookCtrl.currentArticle.title}}</h2>
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