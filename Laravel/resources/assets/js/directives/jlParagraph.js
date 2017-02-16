var dependencies = [
  'leaflet-directive',
  'jetlag.webapp.components.paragraphs',
  'jetlag.webapp.directives.paragraphEditor'
];

angular
  .module('jetlag.webapp.directives.paragraph', dependencies)
  .directive('jlParagraph', JlParagraphDirective)
  .controller('ParagraphController', ParagraphController)
  .filter('paragraphText', ParagraphTextFilter);

ParagraphController.$inject = ['$scope', 'paragraphsService'];
ParagraphTextFilter.$inject = ['$sce'];

function ParagraphController($scope, paragraphsService) {
  var ctrl = this;
  
  $scope.$watch(ctrl.isBeingEdited, function(newValue, oldValue) {
	 if((newValue === true || newValue === false) && newValue !== oldValue) {
		 if(ctrl.model.blockContentType === paragraphsService.contentTypes.MAP) {
			 var place = ctrl.model.blockContent;
			 if(place.marker != null) {
				 location.marker.draggable = newValue;
			 }
		 }
	 } 
  });

  ctrl.getPictureUrl = function() {
    if(ctrl.model.blockContentType === paragraphsService.contentTypes.PICTURE
    && ctrl.model.blockContent != null && ctrl.model.blockContent.bigPicture != null) {
        return ctrl.model.blockContent.bigPicture.url;
    }
  }

  ctrl.isTextType = function() {
    return ctrl.model.blockContentType === paragraphsService.contentTypes.TEXT;
  }

  ctrl.isPictureType = function() {
    return ctrl.model.blockContentType === paragraphsService.contentTypes.PICTURE;
  }

  ctrl.isMapType = function() {
    return ctrl.model.blockContentType === paragraphsService.contentTypes.MAP;
  }

  ctrl.isLinkType = function() {
    return ctrl.model.blockContentType === paragraphsService.contentTypes.LINK;
  }

  ctrl.getMapCenter = function() {
    if(ctrl.isMapType()) {
        return {
            lat: ctrl.model.blockContent.center.latitude,
            lng: ctrl.model.blockContent.center.longitude,
            zoom: ctrl.model.blockContent.zoom
        };
    } else {
        return null;
    }
  }

  ctrl.getMapMarkers = function() {
    if(ctrl.isMapType()
    && ctrl.model.blockContent.place != null
    && ctrl.model.blockContent.marker != null) {
        return [ctrl.model.blockContent.place.marker];
    } else {
        return null;
    }
  }
}

function ParagraphTextFilter($sce) {
  return function (input) {
    var formatedInput = input.replace(new RegExp('\n', 'g'), '<br>');
    return $sce.trustAsHtml(formatedInput);
  }
};

function JlParagraphDirective() {
  return {
    templateUrl: '/templates/jlParagraph.html',
    scope: {},
	bindToController: {
		model: '=',
		isBeingEdited: '&',
		save: '&',
        cancel: '&'
	},
    restrict: 'E',
    controller: 'ParagraphController',
    controllerAs: 'paragraphCtrl'
  }
}