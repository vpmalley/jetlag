var dependencies = [
  'leaflet-directive',
  'jetlag.webapp.components.map',
  'jetlag.webapp.components.paragraphs',
  'jetlag.webapp.directives.paragraphEditor'
];

angular
  .module('jetlag.webapp.directives.paragraph', dependencies)
  .directive('jlParagraph', JlParagraphDirective)
  .controller('ParagraphController', ParagraphController)
  .filter('paragraphText', ParagraphTextFilter);

ParagraphController.$inject = ['$scope', 'paragraphsService', 'mapUidService'];
ParagraphTextFilter.$inject = ['$sce'];

function ParagraphController($scope, paragraphsService, mapUidService) {
  var ctrl = this;
  ctrl.mapUid = mapUidService.generateUid();

  ctrl.getPictureUrl = function() {
    if(ctrl.model.blockContentType === paragraphsService.contentTypes.PICTURE
    && !paragraphsService.isEmpty(ctrl.model)) {
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
        if(ctrl.mapCenter === undefined) {
            ctrl.mapCenter = {};
        }

        ctrl.mapCenter.lat = ctrl.model.blockContent.center.latitude;
        ctrl.mapCenter.lng = ctrl.model.blockContent.center.longitude;
        ctrl.mapCenter.zoom = ctrl.model.blockContent.zoom;

        return ctrl.mapCenter;
    } else {
        return null;
    }
  }

  ctrl.getMapMarkers = function() {
    if(ctrl.isMapType()
    && ctrl.model.blockContent.place != null) {
        if(ctrl.mapMarker === undefined) {
            ctrl.mapMarker = {
                draggable: false,
                focus: false
            };
        }
        ctrl.mapMarker.message = ctrl.model.blockContent.place.label;
        ctrl.mapMarker.lat = ctrl.model.blockContent.place.latitude;
        ctrl.mapMarker.lng = ctrl.model.blockContent.place.longitude;

        if(ctrl.mapMarkers === undefined) {
            ctrl.mapMarkers = [ctrl.mapMarker];
        } else {
            ctrl.mapMarkers[0] = ctrl.mapMarker;
        }
        return ctrl.mapMarkers;
    } else {
        return null;
    }
  }

  ctrl.isEmpty = paragraphsService.isEmpty;
}

function ParagraphTextFilter($sce) {
  return function (input) {
    if(input != null) {
        var formatedInput = input.replace(new RegExp('\n', 'g'), '<br>');
        return $sce.trustAsHtml(formatedInput);
    } else {
        return '';
    }
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