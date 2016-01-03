@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbookCreator">
@endsection

@section('head')
<link href="{{ asset('/css/travelbookCreator.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="jl-tbCreator" ng-controller="TravelbookCreatorController as tbCreatorCtrl">
</div>
@endsection

@section('scripts')
<script src="//localhost/jetlag/Laravel/public/js/travelbookCreator.js"></script>
@endsection