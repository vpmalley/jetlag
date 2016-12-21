@extends('app')

@section('ngApp')
<html lang="en" ng-app="jetlag.webapp.article">
@endsection

@section('head')
<link href="{{ asset('/css/articleCreator.css') }}" rel="stylesheet" type='text/css'>
@endsection

@section('content')
<div class="row" ng-controller="ArticleController as articleCtrl"
	 style="margin-top: 150px">
	<div class="col-xs-12" ng-if="articleCtrl.articleLoaded">

		<div class="article">
		    <jl-paragraph
		        ng-repeat="paragraph in articleCtrl.article.paragraphs"
		        model="paragraph"
			    class="paragraph">
            </jl-paragraph>
		</div> <!-- article -->
	</div>
</div>
@endsection

@section('scripts')
<script>
var preload = {
    articleId: {{ $id }}
}
</script>
<script src="{{ asset('/js/article.js') }}"></script>
@endsection
