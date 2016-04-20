@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.travelbookCreator">
@endsection

@section('head')
<link href="{{ asset('/css/travelbookCreator.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="jl-tbCreator row" ng-controller="TravelbookCreatorController as tbCreatorCtrl" style="margin-top: 150px">
	<!--<div class="col-xs-12">
		<div class="travelbook-controls">
			<div class="jl-card">
				<h1>Avez-vous déjà trouvé un titre ?</h1>
				<p>Court et percutant, il doit donner envie de lire votre histoire.</p>
				<p class="tips">Synonymes de voyage: odyssée, balade, escapade, promenade, trajet, aller-retour,
					vacances, tour du monde, roadtrip, échappée...</p>
				<div class="card-action">
					<input type="text" class="jl-input-text" ng-model="tbCreatorCtrl.travelbook.$attributes.title"/>
				</div>
			</div>
			<div class="jl-card">
				<h1>Déjà des choses à raconter ?</h1>
				<p>Commencez par écrire le 1er article! Vous reviendrez plus tard modifier les informations du carnet de voyage.</p>
				<div class="card-action">
					<div class="jl-btn" ng-click="tbCreatorCtrl.createArticle()"><title>C'est parti !</title></div>
				</div>
			</div>
		</div>
	</div>-->
	<div class="col-xs-offset-2 col-xs-8">
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 1}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(1)">
			<div class="step-number pull-left">
				1
			</div>
			<div class="step-skip pull-right clickable jl-btn jl-btn-empty jl-btn-reverse" ng-click="tbCreatorCtrl.changeTbStep(2); $event.stopPropagation()">
				PASSER
			</div>
			<div class="step-expand pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(1); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Par quoi commencer ?
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="step-body-section-title">
						<div class="jl-btn jl-btn-large" ng-click="tbCreatorCtrl.changeTbStep(2)">Infos du carnet</div>
					</div>
					<div class="step-body-section-caption">
						Pour le préparer à l'avance
					</div>
				</div>
				<div class="step-body-or">ou</div>
				<div class="step-body-section">
					<div class="step-body-section-title">
						<div class="jl-btn jl-btn-large" ng-click="tbCreatorCtrl.createArticle()">1er article</div>
					</div>
					<div class="step-body-section-caption">
						Parce que vous avez déjà des choses à raconter
					</div>
				</div>
			</div>
		</div>
	</div> <!-- to begin -->
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 2}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(2)">
			<div class="step-number pull-left">
				2
			</div>
			<div class="step-skip pull-right clickable jl-btn jl-btn-empty jl-btn-reverse" ng-click="tbCreatorCtrl.changeTbStep(3); $event.stopPropagation()">
				PASSER
			</div>
			<div class="step-expand pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(2); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Dates
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="step-body-section-title">Quand le voyage a-t-il commencé ?</div>
					<div class="step-body-section-caption">Si vous passez cette étape, nous nous baserons sur la date du 1er article.</div>
					<div class="step-body-section-input">
						<input type="date" ng-model="tbCreatorCtrl.travelbook.$attributes.begin_date" />
					</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.$attributes.begin_date != null">
					<div class="step-body-section-title">A-t-il une date de fin connue ?</div>
					<div class="step-body-section-caption">Si vous passez cette étape, nous nous baserons sur la date du dernier article.</div>
					<div class="step-body-section-input">
						<input type="date" ng-model="tbCreatorCtrl.travelbook.$attributes.end_date" />
					</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.$attributes.begin_date != null">
					<div class="step-body-section-title">
						<div class="jl-btn" ng-click="tbCreatorCtrl.changeTbStep(3)"><i class="fa fa-arrow-circle-o-right"></i> Suite</div>
					</div>					
				</div>
			</div>
		</div>
	</div> <!-- Dates -->
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 3}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(3)">
			<div class="step-number pull-left">
				3
			</div>
			<div class="step-skip pull-right clickable jl-btn jl-btn-empty jl-btn-reverse" ng-click="tbCreatorCtrl.changeTbStep(5); $event.stopPropagation()">
				PASSER
			</div>
			<div class="step-expand pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(3); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Titre
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="step-body-section-title">Avez-vous déjà trouvé un titre ?</div>
					<div class="step-body-section-caption">Court et percutant, pour donner envie de lire votre histoire.</div>
					<div class="step-body-section-input">
						<input type="text" ng-model="tbCreatorCtrl.travelbook.$attributes.title"/>
					</div>
					<div class="step-body-section-caption">Synonymes de voyage: odyssée, balade, escapade, promenade, trajet, aller-retour,
					vacances, tour du monde, roadtrip, échappée...</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.$attributes.title != null">
					<div class="step-body-section-title">
						<div class="jl-btn" ng-click="tbCreatorCtrl.changeTbStep(5)"><i class="fa fa-arrow-circle-o-right"></i> Suite</div>
					</div>					
				</div>
			</div>
		</div>
	</div> <!-- Title -->
	<!--
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 4}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(4)">
			<div class="step-number pull-left">
				4
			</div>
			<div class="step-skip pull-right clickable jl-btn jl-btn-empty jl-btn-reverse" ng-click="tbCreatorCtrl.changeTbStep(5); $event.stopPropagation()">
				PASSER
			</div>
			<div class="step-expand pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(4); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Confidentialité
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="step-body-section-title">Par qui le carnet de voyage doit être visible ?</div>
				</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large">Tout le monde</div>
				</div>
				<div class="step-body-or">ou</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large">Mes amis uniquement</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.privacy != null">
					<div class="step-body-section-title">
						<div class="jl-btn" ng-click="tbCreatorCtrl.changeTbStep(5)"><i class="fa fa-arrow-circle-o-right"></i> Suite</div>
					</div>					
				</div>
			</div>
		</div>
	</div> --><!-- privacy -->
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 5}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(5)">
			<div class="step-number pull-left">
				5
			</div>
			<!--
			<div class="step-skip pull-right jl-btn jl-btn-empty jl-btn-reverse" ng-click="tbCreatorCtrl.changeTbStep(); $event.stopPropagation()">
				PASSER
			</div>
			-->
			<div class="step-expand pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(5); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				1er article
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="step-body-section-title">Prêt à écrire le premier chapitre ?</div>
					<div class="step-body-section-caption">Dans les deux cas, vos modifications seront sauvegardées.</div>
				</div>
				<div class="step-body-section">
					<a class="jl-btn jl-btn-large" ng-click="tbCreatorCtrl.createArticle()">Ok c'est parti !</a>
				</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large" ng-click="tbCreatorCtrl.saveTravelbook()">Non pas tout de suite</div>
				</div>
			</div>
		</div>
	</div> <!-- first article -->
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/travelbookCreator.js') }}"></script>
@endsection