@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbook">
@endsection

@section('head')
<link href="{{ asset('/css/travelbook.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="travelbook" ng-controller="TravelbookController as travelbookCtrl">
</div>
@endsection
