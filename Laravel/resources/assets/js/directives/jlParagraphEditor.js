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
  ctrl.leafletMap = { markers: {} };

  var defaultCenter = {
        lat: 45.74,
        lng: 4.87,
        zoom: 8
  }
  
  ctrl.isInputEmpty = paragraphsService.isEmpty;

  ctrl.changeInputType = function(inputType) {
	if(paragraphsService.contentTypes[inputType] !== undefined) {
		ctrl.model.blockContentType = paragraphsService.contentTypes[inputType];

		if(ctrl.model.blockContentType === paragraphsService.contentTypes.MAP) {
			if(ctrl.model.blockContent == null) {
				ctrl.model.blockContent = {};
			}
			if(ctrl.model.blockContent.marker == null) {
				ctrl.model.blockContent.marker = {};
			}
			if(ctrl.model.blockContent.center == null) {
				ctrl.model.blockContent.center = {
				    latitude: defaultCenter.lat,
				    longitude: defaultCenter.lng
				};
				ctrl.model.blockContent.zoom = defaultCenter.zoom;
			}
		}
	}
  }

  ctrl.uploadFiles = function(files) {
    if(files && files.length == 1) {
        /* Do upload the file and associate it with the Picture object */
        pictureUploaderService.upload({
            id: 2, //XXX: fake ID for now because endpoint requires one
            file: files[0],
            caption: ''
        }).then(function(result) {
            console.log('File successfully uploaded', result);
            if(ctrl.model.blockContentType === paragraphsService.contentTypes.PICTURE) {
                ctrl.model.blockContent = result;
            }
        }, function(error) {
            console.log('Error status: ' + error.status);
        });
    }
  }

  ctrl.pictureSelected = function() {
	return ctrl.model.blockContentType === paragraphsService.contentTypes.PICTURE
	&& !_.isEmpty(ctrl.model.blockContent);
  }

  ctrl.changeLocation = function() {
    var place = ctrl.model.blockContent.place;
    if(ctrl.model.blockContentType === paragraphsService.contentTypes.MAP
    && place.label != null) {
	    geocodingService.geocode(place.label)
	    .then(function(results) {
	        if(_.isObject(results) && results.features.length > 0) {
			    var firstMatch = results.features[0];
			    place.label = firstMatch.properties.label;
			    place.latitude = firstMatch.geometry.coordinates[1];
			    place.longitude = firstMatch.geometry.coordinates[0];
		    }
	    });
	}
  }

  function getLeafletMapMarkerCoordinates() {
    if(ctrl.model
    && ctrl.model.blockContentType === paragraphsService.contentTypes.MAP
    && ctrl.model.blockContent.place != null) {
        return ctrl.model.blockContent.place;
    } else {
	  return null;
    }
  }

  $scope.$watch(getLeafletMapMarkerCoordinates, function(newValue, oldValue) {
    if(newValue != null && newValue != oldValue) {
	  ctrl.changeMarkerPosition(newValue);
    }
  });

  ctrl.changeMarkerPosition = function(coordinates) {
    var place = ctrl.model.blockContent.place;

    place.marker = {
	  message: place.label,
	  lat: coordinates.latitude,
	  lng: coordinates.longitude,
	  draggable: true,
	  focus: true
    }
  }

  $scope.$on('leafletDirectiveMarker.bad_id.dragend', function(e, m) {
    var marker, place = ctrl.model.blockContent.place;

	marker = (place.marker = m.model);
    if(marker.lng !== place.longitude ||
    marker.lat !== place.latitude) {
        geocodingService.reverseGeocode(marker)
        .then(function(results) {
            if(_.isObject(results) && results.features.length > 0) {
                var firstMatch = results.features[0];

                place.label = firstMatch.properties.label;
                place.marker.message = firstMatch.properties.label;
            }
        });
    }
  });
  
  ctrl.revert = function() {
    ctrl.blockContent = ctrl._previous.blockContent;
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
        controller._previous = JSON.parse(JSON.stringify(controller.model));
    }
  }
}