angular
  .module('jetlag.webapp.home', ['jetlag.webapp.app'])
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['$scope', 'ModelsManager'];

function HomepageController($scope, ModelsManager) {
	var ctrl = this;
    
    ctrl.isSearching = false;
    
	ctrl.articles = new ModelsManager.ArticleCollection();
	ctrl.articles.fetch();
    
    function searchHasReturned() {
        ctrl.isSearching = false;
    }
    
    ctrl.search = function() {
        ctrl.isSearching = true;
        if(ctrl.searchInput != null && ctrl.searchInput !== '') {
            ctrl.articles.fetch({
                data: {query: ctrl.searchInput},
                success: searchHasReturned,
                error: searchHasReturned
            });
        } else {
            ctrl.articles.fetch({
                success: searchHasReturned,
                error: searchHasReturned
            });
        }
    }
};

