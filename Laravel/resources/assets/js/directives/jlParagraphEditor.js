var dependencies = [
  'ngFileUpload',
  'leaflet-directive',
  'monospaced.elastic',
  'jetlag.webapp.components.geocoding',
  'jetlag.webapp.components.uploader',
  'jetlag.webapp.components.paragraphs'
];

angular
  .module('jetlag.webapp.directives.paragraphEditor', dependencies)
  .directive('jlParagraphEditor', JlParagraphEditorDirective)
  .controller('ParagraphEditorController', ParagraphEditorController);
  

ParagraphEditorController.$inject = ['$scope', 'ModelsManager', '$http', 'pictureUploaderService',
    'JetlagUtils', 'geocodingService', 'paragraphsService'];

function ParagraphEditorController($scope, ModelsManager, $http, pictureUploaderService,
    JetlagUtils, geocodingService, paragraphsService) {
  var ctrl = this;
  ctrl.blockContents = {};
  ctrl.blockContents[paragraphsService.contentTypes.TEXT] = {
    blockContentType: paragraphsService.contentTypes.TEXT,
    blockContent: undefined
  };
  ctrl.blockContents[paragraphsService.contentTypes.PICTURE] = {
    blockContentType: paragraphsService.contentTypes.PICTURE,
    blockContent: undefined
  };
  ctrl.blockContents[paragraphsService.contentTypes.MAP] = {
    blockContentType: paragraphsService.contentTypes.MAP,
    blockContent: undefined
  };
  ctrl.blockContents[paragraphsService.contentTypes.LINK] = {
    blockContentType: paragraphsService.contentTypes.LINK,
    blockContent: undefined
  };
  ctrl.currentContentType = paragraphsService.contentTypes.TEXT;
  var defaultCenter = {
        lat: 45.74,
        lng: 4.87,
        zoom: 8
  };
  var inputMapMarkers = [];
  var inputMapCenter = defaultCenter;
  var previewPicture;
  ctrl.contentTypes = paragraphsService.contentTypes;

  ctrl.isInputEmpty = function() {
    return ctrl.blockContents[ctrl.currentContentType] === undefined
        || paragraphsService.isEmpty(ctrl.blockContents[ctrl.currentContentType]);
  }

  ctrl.isPictureType = function() {
    return ctrl.currentContentType === paragraphsService.contentTypes.PICTURE;
  }

  ctrl.isMapType = function() {
    return ctrl.currentContentType === paragraphsService.contentTypes.MAP;
  }

  ctrl.isTextType = function() {
    return ctrl.currentContentType === paragraphsService.contentTypes.TEXT;
  }

  ctrl.isLinkType = function() {
    return ctrl.currentContentType === paragraphsService.contentTypes.LINK;
  }

  ctrl.changeInputType = function(inputType) {
    if(inputType === paragraphsService.contentTypes.MAP
        && ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent === undefined) {
        var blockContent = {};

        blockContent.center = {
            latitude: defaultCenter.lat,
            longitude: defaultCenter.lng
        };
        blockContent.zoom = defaultCenter.zoom;
        blockContent.place = {
            label: undefined
        };
        ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent = blockContent;
    }
    ctrl.currentContentType = inputType;
  }

  ctrl.getMapCenter = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        return ctrl.inputMapCenter;
    } else {
        return null;
    }
  }

  ctrl.getMapMarkers = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        return ctrl.inputMapMarkers;
    } else {
        return null;
    }
  }

  ctrl.uploadFiles = function(files) {
    if(files && files.length == 1) {
        previewPicture = files[0];
        /* Do upload the file and associate it with the Picture object */
        pictureUploaderService.upload({
            id: 2, //XXX: fake ID for now because endpoint requires one
            file: files[0],
            caption: ''
        }).then(function(result) {
            console.log('File successfully uploaded', result);
            ctrl.blockContents[paragraphsService.contentTypes.PICTURE].blockContent = result;
            previewPicture = undefined;
        }, function(error) {
            console.log('Error status: ' + error.status);
            previewPicture = undefined;
        });
    }
  }

  ctrl.pictureSelected = function() {
	return ctrl.currentContentType === paragraphsService.contentTypes.PICTURE
	&& (previewPicture !== undefined || !paragraphsService.isEmpty(ctrl.blockContents[paragraphsService.contentTypes.PICTURE]));
  }

  ctrl.getPreviewPicture = function() {
    if(!paragraphsService.isEmpty(ctrl.blockContents[paragraphsService.contentTypes.PICTURE])) {
        return ctrl.blockContents[paragraphsService.contentTypes.PICTURE].blockContent.bigPicture.url;
    } else if(previewPicture !== undefined) {
        return previewPicture;
    } else {
        return null;
    }
  }

  ctrl.changeLocation = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        var place = ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent.place;

        if(place.label !== undefined) {
            geocodingService.geocode(place.label)
            .then(function(results) {
                if(_.isObject(results) && results.features.length > 0) {
                    var firstMatch = results.features[0];
                    place.label = firstMatch.properties.label;
                    place.latitude = firstMatch.geometry.coordinates[1];
                    place.longitude = firstMatch.geometry.coordinates[0];
                    updateMarker();
                }
            });
	    }
	}
  }

  /* Since the reference on the Marker object does not change
  * the map won't update manually the marker position when the
  * location changes.
  * So we do it manually.
  */
  function updateMarker() {
    var place = ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent.place;

    inputMapMarkers = [{
        lat: place.latitude,
        lng: place.longitude,
        message: place.label,
        draggable: true,
        focus: false
    }];
  }

  $scope.$on('leafletDirectiveMarker.bad_id.dragend', function(e, m) {
    var marker, place = ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent.place;

	marker = m.model;
    if(marker.lng !== place.longitude ||
        marker.lat !== place.latitude) {
        geocodingService.reverseGeocode(marker)
        .then(function(results) {
            if(_.isObject(results) && results.features.length > 0) {
                var firstMatch = results.features[0];

                place.label = firstMatch.properties.label;
                marker.message = place.label;
            }
        });
    }
  });
  
  ctrl.revert = function() {
    if(ctrl._previous !== undefined) {
        ctrl.model = ctrl._previous;
    }
    ctrl.cancel();
  }

  ctrl.isCreation = function() {
    return ctrl.mode === 'creation';
  }

  function isModeValid() {
    if(['creation', 'edition'].indexOf(ctrl.mode) === -1) {
        console.warn('The `mode` parameter of the jlParagraphEditor directive is not valid.');
    }
  }
  isModeValid();

  /* Init the leaflet map (center + marker) in case there is a current paragraph
  * (e.g. mode === 'edition') and it's of type MAP.
  */
  function initMap() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        var blockContent = ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent;
        inputMapCenter = {
            lat: blockContent.center.latitude,
            lng: blockContent.center.longitude,
            zoom: blockContent.zoom
        };
        if(blockContent.place != null) {
            var place = blockContent.place;

            inputMapMarkers = [{
                lat: place.latitude,
                lng: place.longitude,
                message: place.label,
                draggable: true,
                focus: false
            }];
        }
    }
  }
  initMap();
  
}

function JlParagraphEditorDirective() {
  return {
    templateUrl: '/templates/jlParagraphEditor.html',
    scope: {},
	bindToController: {
		model: '=',
		save: '&',
        cancel: '&',
        mode: '@'
	},
    restrict: 'E',
    controller: 'ParagraphEditorController',
    controllerAs: 'paragraphEditorCtrl',
    link: function(scope, element, attrs, controller) {
        if(controller.model != null) {
            controller._previous = JSON.parse(JSON.stringify(controller.model));
        }
    }
  }
}