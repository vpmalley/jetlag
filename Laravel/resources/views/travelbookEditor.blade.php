@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbookEditor">
@endsection

@section('head')
<!--<link href="{{ asset('/css/travelbookEditor.css') }}" rel="stylesheet" type='text/css'>-->
@endsection

@section('content')
<div class="row" ng-controller="TravelbookEditorController as tbEditorCtrl" style="margin-top: 150px">
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/travelbookEditor.js') }}"></script>
@endsection