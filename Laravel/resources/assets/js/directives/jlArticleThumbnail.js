var dependencies = [
    'jetlag.webapp.base'
];

angular
  .module('jetlag.webapp.directives.articleThumbnail', dependencies)
  .directive('jlArticleThumbnail', JlArticleThumbnailDirective)
  .controller('ArticleThumbnailController', ArticleThumbnailController);


function ArticleThumbnailController() {
    var ctrl = this;

    ctrl.getThumbnailPhoto = function() {
        if(ctrl.model != null) {
            if(ctrl.model.description_media != null) {
                return ctrl.model.descriptionMedia.bigPicture;
            } else {
                return 'url(/images/landscape.jpg)';
            }
        } else {
            return 'url(/images/lake.jpg)';
        }
    }

    ctrl.hasNoPhoto = function() {
        return (ctrl.model != null
        && (ctrl.model.descriptionMedia == null
            || ctrl.model.descriptionMedia.bigPicture == null)
        );
    }

    ctrl.getAuthorsString = function() {
        if(ctrl.model.authors != null) {
            return ctrl.model.authors.join(', ');
        } else {
            return '';
        }
    }

    ctrl.getArticleUrl = function() {
        return '/article/' + ctrl.model.id;
    }

    ctrl.isUserConnected = function() {
        if(ctrl.context != null) {
            var context = ctrl.context();
            return context.isUserConnected === true;
        } else {
            return false;
        }
    }

    ctrl.placeSearchUrl = function() {
        var base = '/home#q=';

        if(ctrl.model != null) {
            return base + encodeURIComponent(ctrl.model.mainPlace.label);
        } else {
            return null;
        }
    }

    ctrl.thumbnailClicked = function() {
        if(ctrl.model != null) {
            window.location.href = ctrl.getArticleUrl();
        }
    }

    ctrl.stopEventPropagation = function(event) {
        event.stopPropagation();
    }
}

function JlArticleThumbnailDirective() {
  return {
    templateUrl: '/templates/jlArticleThumbnail.html',
    scope: {},
	bindToController: {
		model: '=',
		context: '&?'
	},
    restrict: 'E',
    controller: 'ArticleThumbnailController',
    controllerAs: 'articleThumbnailCtrl'
  }
}