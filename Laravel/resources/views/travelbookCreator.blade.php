@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbookCreator">
@endsection

@section('head')
<link href="{{ asset('/css/travelbookCreator.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="jl-tbCreator" ng-controller="TravelbookCreatorController as tbCreatorCtrl"
	style="margin-top: 150px">
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.step === 1}">
		<div class="step-header">
			<div class="step-number pull-left">
				1
			</div>
			<div class="step-skip pull-right">
				<span ng-click="tbCreatorCtrl.changeStep()">PASSER</span>
			</div>
			<div class="step-expand pull-right" ng-click="tbCreatorCtrl.changeStep(1)">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Par quoi commencer ?
			</div>
		</div>
		<div class="step-body clearfix">
			<p>Infos du carnet</p>
			<p>ou</p>
			<p>1er article</p>
		</div>
	</div>
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.step === 2}">
		<div class="step-header">
			<div class="step-number pull-left">
				2
			</div>
			<div class="step-skip pull-right">
				<span ng-click="tbCreatorCtrl.changeStep()">PASSER</span>
			</div>
			<div class="step-expand pull-right" ng-click="tbCreatorCtrl.changeStep(2)">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Dates
			</div>
		</div>
		<div class="step-body clearfix">
			<p>Quand le voyage a-t-il commencé ?</p>
			<p>12/12/12</p>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="//localhost/jetlag/Laravel/public/js/travelbookCreator.js"></script>
@endsection