var dependencies = [
  'jetlag.webapp.app',
  'leaflet-directive',
  'jetlag.webapp.directives.paragraph',
  'jetlag.webapp.components.paragraphs'
];

angular
  .module('jetlag.webapp.article', dependencies)
  .controller('ArticleController', ArticleController);

ArticleController.$inject = ['$scope', '$http',
    'JetlagUtils', 'JLModelsManager', 'paragraphsService'];

function ArticleController($scope, $http,
    JetlagUtils, JLModelsManager, paragraphsService) {
	var ctrl = this;

	ctrl.article = null;
	ctrl.articleLoaded = false;

	function loadArticle(articleID) {
	    ctrl.articleLoaded = false;
		ctrl.article = new JLModelsManager.Article();
		ctrl.article.id = articleID;
		ctrl.article.fetch().then(function() {
		    ctrl.articleLoaded = true;
		    ctrl.article.paragraphs = paragraphsService.getFakeParagraphs()
		});
	}

	/* Use the preload variable */
	if(preload.articleId != null) {
	    loadArticle(preload.articleId);
	}
	
	ctrl.isBeingEdited = function(index) {
		return false;
	}
};