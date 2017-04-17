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
    
    function _search(query) {
        ctrl.isSearching = true;
        if(query !== null) {
            JLModelsManager.Article.fetchCollection({
                url: '/api/0.1/search/articles',
                params: {
                    query: query
                }
            }).then(function(collection) {
                ctrl.articles = collection;
                ctrl.isSearching = false;
            }, function(error) {
                ctrl.isSearching = false;
            });
        } else {
            JLModelsManager.Article.fetchCollection().then(function(collection) {
                ctrl.articles = collection;
                ctrl.isSearching = false;
            }, function(error) {
                ctrl.isSearching = false;
            });
        }
    }

    ctrl.search = function() {
        if(ctrl.searchInput != null && ctrl.searchInput !== '') {
            window.location.hash = 'q=' + encodeURIComponent(ctrl.searchInput);
        } else {
            window.location.hash = '';
        }
    }

    ctrl.getContext = function() {
        return {
            isUserConnected: $scope.isUserConnected
        };
    }

    function parseQuery() {
        var hash = window.location.hash;

        if(hash == null || hash === '') {
            return null;
        }

        var startIdx = hash.indexOf('q=');
        if(startIdx !== -1 && hash.length > startIdx + 2) {
            var endIdx = hash.indexOf('=', startIdx + 2);
            if(endIdx === -1) {
                query = decodeURIComponent(hash.substring(startIdx + 2));
            } else if(endIdx > startIdx + 3) {
                query = decodeURIComponent(hash.substring(startIdx + 2, endIdx));
            } else {
                return null;
            }

            if(query.length > 1) {
                return query;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    $scope.$watch(parseQuery, function(newValue, oldValue) {
        if(newValue !== oldValue) {
            var query = null;

            if(newValue !== null) {
                query = newValue;
            }

            _search(query);
            ctrl.searchInput = query;
        }
    });

    /* If there is already - at init - a query in the URL */
    if(parseQuery() !== null) {
        var query = parseQuery();
        _search(query);
        ctrl.searchInput = query;
    }
};

