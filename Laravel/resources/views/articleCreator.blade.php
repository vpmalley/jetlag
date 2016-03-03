@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.articleCreator">
@endsection

@section('head')
<link href="{{ asset('/css/articleCreator.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="jl-tbCreator row" ng-controller="ArticleCreatorController as articleCreatorCtrl"
	 style="margin-top: 150px">
	<div class="col-xs-12">
	
		<div class="article">
			<div class="paragraph" ng-repeat="paragraph in articleCreatorCtrl.article.$attributes.paragraphs">
				<div class="paragraph-type-text" ng-if="paragraph.type === 'text'">
					<div ng-bind-html="paragraph.text | paragraphText"></div>
				</div>
				<div class="paragraph-type-pictures" ng-if="paragraph.type === 'picture'">
					<div class="paragraph-type-picture" ng-repeat="picture in paragraph.pictures">
						<img class="picture-preview" ngf-thumbnail="picture">
					</div>
					
				</div>
				<div class="paragraph-type-location" ng-if="paragraph.type === 'location'">
					<leaflet id="@{{paragraph.location.id}}" lf-center="paragraph.location.center" 
							 markers="paragraph.location.markers">
					</leaflet>
				</div>
				<div class="paragraph-type-external" ng-if="paragraph.type === 'external'">
					<a ng-href="@{{paragraph.external.link}}" target="_blank">External content here</a>
				</div>
				<div class="paragraph-controls">
					<div ng-if="!$first"><i class="fa fa-arrow-up" ng-click="articleCreatorCtrl.paragraphUp($index)"></i></div>
					<div><i class="fa fa-pencil" ng-click="articleCreatorCtrl.editParagraph($index)"></i></div>
					<div><i class="fa fa-trash" ng-click="articleCreatorCtrl.removeParagraph($index)"></i></div>
					<div ng-if="!$last"><i class="fa fa-arrow-down" ng-click="articleCreatorCtrl.paragraphDown($index)"></i></div>
				</div>
			</div> <!-- paragraph -->
		</div> <!-- article -->
	
		<div class="article-controls">
			<div class="jl-card">
				<h1>Ajoutez un paragraphe</h1>
				<p class="paragraph-tips">
					Vous êtes allé au restaurant ? Chaque culture a sa manière de présenter les plats et de dresser la table.
				</p>
				<p>Un même paysage pris à deux instants différents peut réserver de sacrées surprises!</p>
				<div class="card-action">
					<form name="paragraphEditor" novalidate>
						<div class="paragraph-editor">
							<div class="btn-group btn-group-justified">
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'text'}"
								ng-click="articleCreatorCtrl.changeInputType('text')"><i class="fa fa-pencil"></i></div>
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'picture'}"
								ng-click="articleCreatorCtrl.changeInputType('picture')"><i class="fa fa-picture-o"></i></div>
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'location'}"
								ng-click="articleCreatorCtrl.changeInputType('location')"><i class="fa fa-map-marker"></i></div>
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'external'}"
								ng-click="articleCreatorCtrl.changeInputType('external')"><i class="fa fa-link"></i></div>
							</div>
							<div class="paragraph-input">
								<textarea 	ng-if="articleCreatorCtrl.paragraphEditor.input.type === 'text'"
											placeholder="Lisez nos conseils juste au dessus si vous êtes bloqués"
											ng-model="articleCreatorCtrl.paragraphEditor.input.text"
											name="text"
											msd-elastic="\n">
								</textarea>
								<div ng-if="articleCreatorCtrl.paragraphEditor.input.type === 'picture'">
									<div 	ngf-drop="articleCreatorCtrl.uploadFiles($files)" class="drop-box"
											ngf-drag-over-class="'dragover'"
											ngf-pattern="'image/*'"
											ng-disabled="articleCreatorCtrl.pictureSelected()">
										<div class="jl-btn jl-btn-lg" ng-class="{'jl-btn-disabled': articleCreatorCtrl.pictureSelected()}"
										ngf-select="articleCreatorCtrl.uploadFiles($files)"
										ngf-pattern="'image/*'" ng-disabled="articleCreatorCtrl.pictureSelected()">
											Select a picture
										</div>
										<div class="strut"></div><div class="drop-text">Drop a picture here</div>
									</div>
									<img class="picture-preview" ngf-thumbnail="articleCreatorCtrl.paragraphEditor.input.picture">
								</div>
								<div ng-if="articleCreatorCtrl.paragraphEditor.input.type === 'location'">
									<div class="jl-table location-searchbar">
										<input class="jl-table-cell" style="width:90%" type="text" name="name" placeholder="Pays, ville, adresse..."
										ng-model="articleCreatorCtrl.paragraphEditor.input.location.name"></input>
										<button  class="jl-table-cell jl-btn jl-btn-large" ng-click="articleCreatorCtrl.changeLocation()">Search</button>
									</div>
									<leaflet 	id="paragraphEditorMap" lf-center="articleCreatorCtrl.leafletMap.center"
												markers="articleCreatorCtrl.leafletMap.markers" events="articleCreatorCtrl.leafletMap.events">
									</leaflet>
								</div>
								<div ng-if="articleCreatorCtrl.paragraphEditor.input.type === 'external'">
									<input type="text" class="external-bar" name="external" placeholder="Entrez l'URL ici"
									ng-model="articleCreatorCtrl.paragraphEditor.input.external.link"></input>
									<p>Une vignette représentant le contenu de la page distante sera insérée dans votre article</p>
									<div class="external-preview">
									</div>
								</div>
							</div>
						</div>
						<div style="text-align: right" ng-if="!articleCreatorCtrl.isInputEmpty()">
							<div class="jl-btn jl-btn-lg jl-btn-empty" ng-click="articleCreatorCtrl.resetParagraph()">
								<i class="fa fa-times"></i> Supprimer
							</div><!--
							--><div class="jl-btn jl-btn-lg" ng-click="articleCreatorCtrl.addParagraph()">
								<i class="fa fa-plus"></i> Ajouter
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<div class="article-publisher jl-card">
				<h1>Votre article est terminé ?</h1>
				<div class="card-action">
					<div class="jl-btn-group">
						<div class="jl-btn jl-btn-empty"><title>Abandonner</title><br><small>(et perdre les changements)</small></div>
						<div class="jl-btn jl-btn-empty"><title>Quitter</title><br><small>(en sauvegardant)</small></div>
						<div class="jl-btn"><title>Publier</title></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/articleCreator.js') }}"></script>
@endsection