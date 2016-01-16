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
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 1}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(1)">
			<div class="step-number pull-left">
				1
			</div>
			<div class="step-skip pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(2); $event.stopPropagation()">
				<span>PASSER</span>
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
						<div class="jl-btn jl-btn-large" ng-click="console.log('not implemented yet!')">1er article</div>
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
			<div class="step-skip pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(3); $event.stopPropagation()">
				<span>PASSER</span>
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
					<div class="step-body-section-caption">Si vous passez, nous nous baserons sur la date du 1er article.</div>
					<div class="step-body-section-input">
						<input type="date" ng-model="tbCreatorCtrl.travelbook.date" placeholder="date" />
					</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.date != null">
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
			<div class="step-skip pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(4); $event.stopPropagation()">
				<span>PASSER</span>
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
						<input type="text" ng-model="tbCreatorCtrl.travelbook.title"/>
					</div>
					<div class="step-body-section-caption">Synonymes de voyage: odyssée, balade, escapade, promenade, trajet, aller-retour,
					vacances, tour du monde, roadtrip, échappée...</div>
				</div>
				<div class="step-body-section" ng-if="tbCreatorCtrl.travelbook.title != null">
					<div class="step-body-section-title">
						<div class="jl-btn" ng-click="tbCreatorCtrl.changeTbStep(4)"><i class="fa fa-arrow-circle-o-right"></i> Suite</div>
					</div>					
				</div>
			</div>
		</div>
	</div> <!-- Title -->
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 4}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(4)">
			<div class="step-number pull-left">
				4
			</div>
			<div class="step-skip pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(5); $event.stopPropagation()">
				<span>PASSER</span>
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
	</div> <!-- privacy -->
	<div class="step" ng-class="{'step-open': tbCreatorCtrl.tbStep === 5}">
		<div class="step-header clickable" ng-click="tbCreatorCtrl.toggleTbStep(5)">
			<div class="step-number pull-left">
				5
			</div>
			<div class="step-skip pull-right clickable" ng-click="tbCreatorCtrl.changeTbStep(); $event.stopPropagation()">
				<span>PASSER</span>
			</div>
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
				</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large">Ok c'est parti !</div>
				</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large">Non pas tout de suite</div>
				</div>
			</div>
		</div>
	</div> <!-- first article -->
</div>
<div class="jl-tbCreator" ng-controller="ArticleCreatorController as articleCreatorCtrl"
	 style="margin-top: 150px">
	<div class="step" ng-class="{'step-open': articleCreatorCtrl.articleStep === 1}">
		<div class="step-header clickable" ng-click="articleCreatorCtrl.toggleArticleStep(1)">
			<div class="step-number pull-left">
				1
			</div>
			<div class="step-skip pull-right clickable" ng-click="articleCreatorCtrl.changeArticleStep(); $event.stopPropagation()">
				<span>PASSER</span>
			</div>
			<div class="step-expand pull-right clickable" ng-click="articleCreatorCtrl.changeArticleStep(1); $event.stopPropagation()">
				<i class="fa fa-fw fa-chevron-down"></i>
			</div>
			<div class="step-title center-block">
				Par quoi commencer ?
			</div>
		</div>
		<div class="step-body clearfix">
			<div class="step-body-content">
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large" ng-click="articleCreatorCtrl.changeArticleStep(2)">Infos de l'article</div>
				</div>
				<div class="step-body-or">ou</div>
				<div class="step-body-section">
					<div class="jl-btn jl-btn-large" ng-click="articleCreatorCtrl.changeArticleStep(4)">Non pas tout de suite</div>
				</div>
			</div>
		</div>
	</div> <!-- to begin -->
	
	<div class="article">
		<div class="paragraph" ng-repeat="paragraph in articleCreatorCtrl.paragraphs">
		</div>
	</div>
	<div class="paragraph-editor">
		<h1>Ajoutez un paragraphe</h1>
		<p class="paragraph-tips">
		Vous êtes allé au restaurant ? Chaque culture a sa manière de présenter les plats et de dresser la table.
		</p>
		<div class="btn-group btn-group-justified">
			<div class="btn btn-default"><i class="fa fa-plus"></i> Ajouter</div>
			<div class="btn btn-default"><i class="fa fa-times"></i> Supprimer</div>
		</div>
		<div class="btn-group btn-group-justified">
			<div class="btn btn-default"><i class="fa fa-pencil"></i></div>
			<div class="btn btn-default"><i class="fa fa-picture-o"></i></div>
			<div class="btn btn-default"><i class="fa fa-map-marker"></i></div>
			<div class="btn btn-default"><i class="fa fa-link"></i></div>
		</div>
		<div class="article-input">
			<textarea 	ng-if="articleCreatorCtrl.paragraphEditor.contentType === 'text'"
						placeholder="Lisez nos conseils juste au dessus si vous êtes bloqués"
						ng-model="articleCreatorCtrl.paragraphEditor.content">
			</textarea>
		</div>
	</div>
	<div class="article-publisher">
		<div class="btn-group btn-group-justified">
			<div class="btn btn-default">Abandonner<br>(en sauvegardant)</div>
			<div class="btn btn-default">Publier</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="//localhost/jetlag/Laravel/public/js/travelbookCreator.js"></script>
@endsection