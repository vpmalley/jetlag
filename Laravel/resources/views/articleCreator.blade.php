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
	<div class="col-xs-12" ng-if="articleCreatorCtrl.articleLoaded">
	
		<div class="article">
		    <jl-paragraph
		        ng-repeat-start="paragraph in articleCreatorCtrl.article.$attributes.paragraphs"
		        model="paragraph"
			    class="paragraph editor"
                is-being-edited="articleCreatorCtrl.isBeingEdited($index)"
                save="articleCreatorCtrl.saveParagraph($index)"
                cancel="articleCreatorCtrl.cancelParagraphEdition()">
            </jl-paragraph>
			<div class="paragraph-controls" ng-repeat-end>
				<div ng-if="!$first" class="clickable">
				    <i class="fa fa-arrow-up" ng-click="articleCreatorCtrl.paragraphUp($index)"></i>
				</div>
				<div class="clickable">
				    <i class="fa fa-pencil"
				        ng-if="!articleCreatorCtrl.isBeingEdited($index)"
				        ng-click="articleCreatorCtrl.editParagraph($index)"></i>
				</div>
				<div class="clickable">
				    <i class="fa fa-trash" ng-click="articleCreatorCtrl.removeParagraph($index)"></i>
				</div>
				<div ng-if="!$last" class="clickable">
				    <i class="fa fa-arrow-down" ng-click="articleCreatorCtrl.paragraphDown($index)"></i>
				</div>
			</div>
		</div> <!-- article -->
	
		<div class="article-controls">
			<div class="jl-card">
				<h1>Ajoutez un paragraphe</h1>
				<p class="paragraph-tips">
					Vous êtes allé au restaurant ? Chaque culture a sa manière de présenter les plats et de dresser la table.
				</p>
				<p class="paragraph-tips">
					Un même paysage pris à deux instants différents peut réserver de sacrées surprises!
				</p>
				<div class="card-action">
					<form name="paragraphEditor" novalidate>
						<div class="paragraph-editor">
							<div class="btn-group btn-group-justified">
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'text'}"
								ng-click="articleCreatorCtrl.changeInputType('text')"><i class="fa fa-pencil"></i></div>
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'picture'}"
								ng-click="articleCreatorCtrl.changeInputType('picture')"><i class="fa fa-camera-retro"></i></div>
								<div class="btn jl-tab" ng-class="{'selected':articleCreatorCtrl.paragraphEditor.input.type === 'location'}"
								ng-click="articleCreatorCtrl.changeInputType('location')"><i class="fa fa-map"></i></div>
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
										<div class="jl-btn jl-btn-big"
										ngf-select="articleCreatorCtrl.uploadFiles($files)"
										ngf-pattern="'image/*'">
											Select a picture
										</div>
										<div class="strut"></div><div class="drop-text">Drop a picture here</div>
									</div>
									<img class="picture-preview" ng-if="articleCreatorCtrl.paragraphEditor.input.picture.size > 0" ngf-thumbnail="articleCreatorCtrl.paragraphEditor.input.picture">
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
							<div class="jl-btn jl-btn-big jl-btn-empty" ng-click="articleCreatorCtrl.resetParagraph()">
								<i class="fa fa-times"></i> Supprimer
							</div><!--
							--><div class="jl-btn jl-btn-big" ng-click="articleCreatorCtrl.addParagraph()">
								<i class="fa fa-plus"></i> Ajouter
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<div class="jl-card">
				<h1>Donnez un titre à votre histoire (obligatoire)</h1>
				<p>Court et percutant, évitez les jeux de mots trop simples.</p>
				<div class="card-action">
					<input type="text" class="jl-input-text" ng-model="articleCreatorCtrl.article.$attributes.title"/>
				</div>
			</div>
			<div class="article-publisher jl-card">
				<h1>Votre article est terminé ?</h1>
				<div class="clickable" ng-if="articleCreatorCtrl.errors" ng-click="articleCreatorCtrl.dismissErrors()">
					<p ng-repeat="error in articleCreatorCtrl.errors" class="alert alert-danger">
						@{{error}}
					</p>
				</div>
				<div class="card-action">
					<div class="jl-btn-group">
						<div class="jl-btn jl-btn-empty" ng-click="articleCreatorCtrl.loseArticle()"><title>Abandonner</title><br><small>(et perdre les changements)</small></div>
						<div class="jl-btn jl-btn-empty" ng-click="articleCreatorCtrl.saveOnlyArticle()"><title>Quitter</title><br><small>(en sauvegardant)</small></div>
						<div class="jl-btn" ng-click="articleCreatorCtrl.publishArticle()"><title>Publier</title></div>
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