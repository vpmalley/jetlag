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
		        ng-repeat-start="paragraph in articleCreatorCtrl.article.paragraphs"
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
				    <jl-paragraph-editor model="articleCreatorCtrl.paragraphEditor"
				                         mode="creation"
                                         save="articleCreatorCtrl.addParagraph()"
                                         cancel="articleCreatorCtrl.resetParagraphEditor()">
                    </jl-paragraph-editor>
				</div>
			</div>
			
			<div class="jl-card">
				<h1>Donnez un titre à votre histoire (obligatoire)</h1>
				<p>Court et percutant, évitez les jeux de mots trop simples.</p>
				<div class="card-action">
					<input type="text" class="jl-input-text" ng-model="articleCreatorCtrl.article.title"/>
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
<script>
var preload = {
    articleId: {{ $id }}
}
</script>
<script src="{{ asset('/js/articleCreator.js') }}"></script>
@endsection