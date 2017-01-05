angular
  .module('jetlag.webapp.user', ['jetlag.webapp.app'])
  .controller('UserController', UserController);

UserController.$inject = ['$scope', 'ModelsManager', 'JLModelsManager'];

function UserController($scope, MM, JLModelsManager) {
	var ctrl = this;
    
    /* OLD WAY 
    
    ctrl.articles = new MM.ArticleCollection();
	ctrl.articles.fetch();
    */
    
    /* NEW WAY */
    ctrl.articles = [];
    JLModelsManager.Article.fetchCollection().then(function(collection) {
        ctrl.articles = collection;
    });
};

