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
  ctrl.blockContents = undefined;
  ctrl.currentContentType = undefined;
  var defaultCenter = {
        lat: 45.74,
        lng: 4.87,
        zoom: 8
  };
  var inputMapMarkers = [], inputMapCenter, previewPicture;
  ctrl.contentTypes = paragraphsService.contentTypes;
  resetBlockContents();

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
    if(inputType === paragraphsService.contentTypes.MAP)
        /* Need to fill the MAP blockContent with default values */
        if(ctrl.blockContents[paragraphsService.contentTypes.MAP].blockContent == null) {
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
        /* Need to update the map center and map markers with the MAP blockContent */
        else {
            initMap();
        }
    ctrl.currentContentType = inputType;
  }

  ctrl.getMapCenter = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        return inputMapCenter;
    } else {
        return null;
    }
  }

  ctrl.getMapMarkers = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        return inputMapMarkers;
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

    if(inputMapMarkers.length === 1) {
        inputMapMarkers[0] = {
            lat: place.latitude,
            lng: place.longitude,
            message: place.label,
            draggable: true,
            focus: false
        };
    } else {
        inputMapMarkers.push({
            lat: place.latitude,
            lng: place.longitude,
            message: place.label,
            draggable: true,
            focus: false
        });
    }
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
        ctrl.model.blockContent = ctrl._previous.blockContent;
        ctrl.model.blockContentType = ctrl._previous.blockContentType;
    }
    resetBlockContents();
    ctrl.cancel();
  }

  function resetBlockContents() {
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
      inputMapCenter = angular.copy(defaultCenter);
      inputMapMarkers.length = 0;
  }

  ctrl.update = function() {
    if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
        var mapBlockContent = ctrl.blockContents[ctrl.currentContentType].blockContent;
        mapBlockContent.zoom = inputMapCenter.zoom;
        mapBlockContent.center = {
            latitude: inputMapCenter.lat,
            longitude: inputMapCenter.lng
        };
        if(inputMapMarkers.length > 0) {
            mapBlockContent.place.latitude = inputMapMarkers[0].lat;
            mapBlockContent.place.longitude = inputMapMarkers[0].lng;
        }

    }

    ctrl.model.blockContent = ctrl.blockContents[ctrl.currentContentType].blockContent;
    ctrl.model.blockContentType = ctrl.currentContentType;
    ctrl.save();
    resetBlockContents();
    ctrl._previous = undefined;
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

  ctrl.initFromModel = function() {
    if(ctrl.model == null) {
        console.error('Model is null in jlParagraphEditor');
        return;
    } else if(ctrl.model.blockContentType == null
        || !paragraphsService.isValidContentType(ctrl.model.blockContentType)) {
        console.error('Model is of unexpected type in jlParagraphEditor');
        return;
    } else {
        ctrl.blockContents[ctrl.model.blockContentType].blockContent = ctrl.model.blockContent;
        ctrl.currentContentType = ctrl.model.blockContentType;
        if(ctrl.currentContentType === paragraphsService.contentTypes.MAP) {
            initMap();
        }
    }
  }
  
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
        controller.initFromModel()
    }
  }
}