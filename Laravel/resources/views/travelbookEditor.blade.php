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
	    <h1>Aller-retour d'un nain en Irlande</h1>
		<p class="description">Au pays de la Guinness, des bus à deux étages, du hurling, des trèfles à quatre feuilles 
		et des paysages qui en mettent plein la vue !</p>
		<div class="social"></div>
	  </div>
	  <div class="header-bottom">
	    <div class="jl-btn jl-btn-empty">EDIT</div>
		<div class="authors">Val LeNain, Thierry, Belette, Skread</div>
	  </div>
	</div>
	<div class="travelbook-content-table">
	</div>
	<div class="travelbook-first-article">
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/travelbookEditor.js') }}"></script>
@endsection