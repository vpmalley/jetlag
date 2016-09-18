angular
  .module('jetlag.webapp.user', ['jetlag.webapp.app'])
  .controller('UserController', UserController);

UserController.$inject = ['$scope', 'ModelsManager'];

function UserController($scope, ModelsManager) {
	var ctrl = this;
    
    ctrl.articles = new ModelsManager.ArticleCollection();
	ctrl.articles.fetch();
};

