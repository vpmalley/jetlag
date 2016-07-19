angular
  .module('jetlag.webapp.home', ['jetlag.webapp.app'])
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['$scope', 'ModelsManager'];

function HomepageController($scope, ModelsManager) {
	var ctrl = this;
    
	ctrl.articles = new ModelsManager.ArticleCollection();
	ctrl.articles.fetch();
    
    ctrl.search = function() {
        if(ctrl.searchInput != null && ctrl.searchInput !== '') {
            ctrl.articles.fetch({
                data: {query: ctrl.searchInput}
            });
        } else {
            ctrl.articles.fetch();
        }
    }
};

