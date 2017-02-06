var dependencies = [
  'jetlag.webapp.app',
  'leaflet-directive',
  'jetlag.webapp.directives.paragraph'
];

angular
  .module('jetlag.webapp.article', dependencies)
  .controller('ArticleController', ArticleController);

ArticleController.$inject = ['$scope', 'ModelsManager', '$http',
    'JetlagUtils', 'JLModelsManager'];

function ArticleController($scope, ModelsManager, $http,
    JetlagUtils, JLModelsManager) {
	var ctrl = this;

	ctrl.article = null;
	ctrl.mm = ModelsManager;
	ctrl.articleLoaded = false;

	function loadArticle(articleID) {
	    ctrl.articleLoaded = false;
		ctrl.article = new JLModelsManager.Article();
		ctrl.article.id = articleID;
		ctrl.article.fetch().then(function() {
		    ctrl.articleLoaded = true;
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