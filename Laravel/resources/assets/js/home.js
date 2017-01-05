angular
  .module('jetlag.webapp.home', ['jetlag.webapp.app'])
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['$scope', 'ModelsManager', 'JLModelsManager'];

function HomepageController($scope, MM, JLModelsManager) {
	var ctrl = this;
    
    ctrl.isSearching = false;
    
	/* OLD WAY

	ctrl.articles = new MM.ArticleCollection();
	ctrl.articles.fetch();
    */

    /* NEW WAY */
    ctrl.articles = [];
    JLModelsManager.Article.fetchCollection().then(function(collection) {
        ctrl.articles = collection;
    });

    ctrl.articleAt = function(idx) {
        if(idx >= 0 && ctrl.articles.length > 0 && idx < ctrl.articles.length) {
            return ctrl.articles[idx] != null ? ctrl.articles[idx] : null;
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

