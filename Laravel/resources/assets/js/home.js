angular
  .module('jetlag.webapp.home', ['jetlag.webapp.app'])
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['$scope', 'ModelsManager'];

function HomepageController($scope, ModelsManager) {
	var ctrl = this;
    
	ctrl.articles = new ModelsManager.ArticleCollection();
	ctrl.articles.fetch();
};

