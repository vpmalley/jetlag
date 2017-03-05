(function(window) {
    'use strict';

var angular = window.angular;
var $ = window.$;

var dependencies = [
    'jetlag.webapp.app', 
    'ngFileUpload',
    'leaflet-directive',
    'monospaced.elastic',
    'jetlag.webapp.directives.paragraph',
    'jetlag.webapp.directives.inContextEditable',
    'moment-picker'
];
    
angular
  .module('jetlag.webapp.travelbook', dependencies)
  .controller('TravelbookController', TravelbookController);

TravelbookController.$inject = ['$scope', 'JLModelsManager'];

function TravelbookController($scope, JLModelsManager) {
	var ctrl = this;
	
	ctrl.travelbook = null;
	ctrl.travelbookLoaded = false;
    ctrl.currentArticle = null;
    ctrl.mode = "display";

    function loadTravelbook(travelbookId) {
	    ctrl.travelbookLoaded = false;
		ctrl.travelbook = new JLModelsManager.Travelbook();
		ctrl.travelbook.id = travelbookId;
		ctrl.travelbook.fetch().then(function() {
		    ctrl.travelbookLoaded = true;
		    if(ctrl.travelbook.articles != null && ctrl.travelbook.articles.length > 0) {
		        ctrl.currentArticle = ctrl.travelbook.articles[0];
		    }
		});
	}

	/* Use the preload variable */
	if(preload.travelbookId != null) {
	    loadTravelbook(preload.travelbookId);
	}
    
    ctrl.editTravelbook = function() {
        // TODO: check current user has right to
        if(ctrl.mode === "display") {
            ctrl.mode = "edit";
        } else if(ctrl.mode === "edit") {
            // TODO: check unsaved changes
            ctrl.mode = "display";
        }
    }
    
    ctrl.isBeingEdited = function() {
        return ctrl.mode === "edit";
    }
    
    ctrl.navigateTo = function(article) {
        if(article !== null) {
            window.location.hash = article.id;
        }
    }
    
    function filterInt(val) {
      if(/^[0-9]+$/.test(val)) {
        return Number(val);
      }
      return NaN;
    }
    
    function parseHash(hash) {
        var articleID = null;
        var idx = hash.indexOf('/');
        
        if(idx !== -1 && hash.length > idx + 1 ) {
            var sub = hash.substring(idx + 1, hash.length);
            
            sub = filterInt(sub);
            if(!isNaN(sub)) {
                articleID = sub;
            }
        }
        
        return articleID;
    }
    
    $scope.$watch(function() { return window.location.hash; }, function(newValue, oldValue) {
       var articleID = parseHash(newValue);
       
       if(articleID !== null) {
           ctrl.travelbook.articles.find(function(article) { //TODO: $attributes
               if(article.id === articleID) {
                   ctrl.currentArticle = article;
                   return true;
               }
               return false;
           });
       } else {
            ctrl.currentArticle = null;
       }
    });
    
    ctrl.hasPreviousArticle = function() {
        var ret = false;
        if(ctrl.travelbook != null && ctrl.currentArticle !== null) {
            ctrl.travelbook.articles.some(function(article, idx) {
               if(article.id === ctrl.currentArticle.id) {
                   ret = idx > 0;
                   return true;
               }
               return false;
            });
        }
        return ret;
    }
    
    ctrl.hasNextArticle = function() {
        var ret = false;
        if(ctrl.travelbook != null && ctrl.currentArticle !== null) {
            ctrl.travelbook.articles.some(function(article, idx) {
               if(article.id === ctrl.currentArticle.id) {
                   ret = ctrl.travelbook.articles.length > (idx + 1);
                   return true;
               }
               return false;
            });
        }
        return ret;
    }
    
    ctrl.navigateToPrevious = function() {
        if(ctrl.currentArticle === null || !ctrl.hasPreviousArticle(ctrl.currentArticle)) {
          return;  
        } 
        
        ctrl.travelbook.articles.some(function(article, idx) {
           if(article.id === ctrl.currentArticle.id) {
               ctrl.navigateTo(ctrl.travelbook.articles[idx - 1]);
               return true;
           } 
           return false;
        });
        
    }
    
    ctrl.navigateToNext = function() {
        if(ctrl.currentArticle === null || !ctrl.hasNextArticle(ctrl.currentArticle)) {
          return;  
        } 
        
        ctrl.travelbook.articles.some(function(article, idx) {
           if(article.id === ctrl.currentArticle.id) {
               ctrl.navigateTo(ctrl.travelbook.articles[idx + 1]);
               return true;
           } 
           return false;
        });
    }
    
    ctrl.createNewArticle = function() {
        var newArticle = new JLModelsManager.Article();
        newArticle.travelbook = {
            id: ctrl.travelbook.id
        };
        newArticle.save()
        .success(function(article) {
            window.location.href = window.location.protocol + "//" + window.location.host
                + "/articles/create/#/" + article.id;
        })
        .error(function(error) {
            console.log('Error when creating new article', error);
        });
    }
};

})(window);

