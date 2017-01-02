angular
  .module('jetlag.webapp.home', ['jetlag.webapp.app'])
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['$scope', 'ModelsManager', 'ModelsMaker', 'JLModelsManager'];

function HomepageController($scope, ModelsManager, ModelsMaker, MM) {
	var ctrl = this;
    
    ctrl.isSearching = false;
    ctrl.mm = ModelsMaker;
    ctrl.MM = MM;
    
	/* OLD WAY

	ctrl.articles = new ModelsManager.ArticleCollection();
	ctrl.articles.fetch();
    */

    /* NEW WAY */
    ctrl.articles = [];
    MM.Article.fetchCollection().then(function(collection) {
        ctrl.articles = collection;
    });

    ctrl.articleAt = function(idx) {
        if(idx >= 0 && ctrl.articles.length > 0 && idx < ctrl.articles.length) {
            return ctrl.articles[idx];
        } else {
            return null;
        }
    }

    function searchHasReturned() {
        ctrl.isSearching = false;
    }
    
    ctrl.search = function() {
        ctrl.isSearching = true;
        if(ctrl.searchInput != null && ctrl.searchInput !== '') {
            MM.Article.fetchCollection({
                url: '/api/0.1/search/articles',
                params: {
                    query: ctrl.searchInput
                }
            }).then(function(collection) {
                ctrl.articles = collection;
                searchHasReturned();
            }, function(error) {
                searchHasReturned();
            });
        } else {
            MM.Article.fetchCollection().then(function(collection) {
                ctrl.articles = collection;
                searchHasReturned();
            }, function(error) {
                searchHasReturned();
            });
        }
    }
};

