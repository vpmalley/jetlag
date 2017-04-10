var dependencies = [
    'jetlag.webapp.app',
    'jetlag.webapp.directives.articleThumbnail'
];

angular
  .module('jetlag.webapp.home', dependencies)
  .controller('HomepageController', HomepageController);

HomepageController.$inject = ['JLModelsManager', '$scope'];

function HomepageController(JLModelsManager, $scope) {
	var ctrl = this;
    
    ctrl.isSearching = false;
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
            JLModelsManager.Article.fetchCollection({
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
            JLModelsManager.Article.fetchCollection().then(function(collection) {
                ctrl.articles = collection;
                searchHasReturned();
            }, function(error) {
                searchHasReturned();
            });
        }
    }

    ctrl.getContext = function() {
        return {
            isUserConnected: $scope.isUserConnected
        };
    }
};

