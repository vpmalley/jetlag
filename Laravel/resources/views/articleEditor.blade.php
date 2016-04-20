@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.articleEditor">
@endsection

@section('head')
<!--<link href="{{ asset('/css/articleEditor.css') }}" rel="stylesheet" type='text/css'>-->
@endsection

@section('content')
<div class="row" ng-controller="ArticleEditorController as articleEditorCtrl" style="margin-top: 150px">
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/articleEditor.js') }}"></script>
@endsection