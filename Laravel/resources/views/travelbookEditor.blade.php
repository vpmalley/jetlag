@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbookEditor">
@endsection

@section('head')
<link href="{{ asset('/css/travelbook.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="travelbook" ng-controller="TravelbookEditorController as tbEditorCtrl">
	<div class="travelbook-header">
	  <div class="header-top" style="background-image:url({{ asset('/images/4.jpg') }})">
	    <div class="header-top-content">
	      <h1>Aller-retour d'un nain en Irlande</h1>
		  <p class="description">Au pays de la Guinness, des bus à deux étages, du hurling, des trèfles à quatre feuilles 
		  et des paysages qui en mettent plein la vue !</p>
		  <div class="space-and-time">
		    <span><i class="fa fa-fw fa-map-marker"></i> Dublin, Ireland</span>
		    <span><i class="fa fa-fw fa-calendar-o"></i> 12.03.2014</span>
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
	  <div class="alert alert-warning">
	    This content table should be either a map or a calendar, I don't think a basic list is useful.
	  </div>
	  <div class="entries-list">
		<div class="content-entry">
			<h2 class="entry-title">
			  Landing in Dublin
			</h2>
			<div class="entry-details">
			  <span><i class="fa fa-fw fa-map-marker"></i> Dublin, Ireland</span>
			  <span><i class="fa fa-fw fa-calendar-o"></i> 12.03.2014</span>
			</div>
		</div>
		<div class="content-entry">
			<h2 class="entry-title">
			  Going west to the Arran Islands
			</h2>
			<div class="entry-details">
			  <span><i class="fa fa-fw fa-map-marker"></i> Arran Islands, Ireland</span>
			  <span><i class="fa fa-fw fa-calendar-o"></i> 12.04.2014</span>
			</div>
		</div>
	  </div>
	</div>
	<div class="travelbook-first-article">
		<div class="article-nav-buttons">
			<div class="jl-btn-group">
				<div class="jl-btn jl-btn-empty">Précédent</div>
				<div class="jl-btn jl-btn-empty">Suivant</div>
			</div>
		</div>
		<div class="article-header">
			<h2 class="article-title">Landing in Dublin</h2>
			<div class="article-details">
				<span><i class="fa fa-fw fa-map-marker"></i>Dublin, Ireland</span>
				<span><i class="fa fa-fw fa-calendar-o"></i>12.03.2014</span>
			</div>
		</div>
		<div class="article-body">
		</div>
		<div class="article-nav-buttons">
			<div class="jl-btn-group">
				<div class="jl-btn jl-btn-empty">Précédent</div>
				<div class="jl-btn jl-btn-empty">Suivant</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/travelbookEditor.js') }}"></script>
@endsection