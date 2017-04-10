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
        return ctrl.model.url;
    }

    ctrl.isUserConnected = function() {
        if(ctrl.context != null) {
            var context = ctrl.context();
            return context.isUserConnected === true;
        } else {
            return false;
        }
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