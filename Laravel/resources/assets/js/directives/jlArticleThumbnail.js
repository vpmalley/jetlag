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
        if(ctrl.model.description_media != null) {
            return ctrl.model.descriptionMedia.bigPicture;
        } else {
            return '';
        }
    }

    ctrl.getAuthorsString = function() {
        if(ctrl.model.authors != null) {
            return ctrl.model.authors.join(', ');
        } else {
            return '';
        }
    }

    ctrl.getArticleUrl = function() {
        return model.url;
    }
}

function JlArticleThumbnailDirective() {
  return {
    templateUrl: '/templates/jlArticleThumbnail.html',
    scope: {},
	bindToController: {
		model: '='
	},
    restrict: 'E',
    controller: 'ArticleThumbnailController',
    controllerAs: 'articleThumbnailCtrl'
  }
}