var dependencies = [
  'ngFileUpload',
  'leaflet-directive',
  'monospaced.elastic'
];

angular
  .module('jetlag.webapp.directives.paragraphEditor', dependencies)
  .directive('jlParagraphEditor', JlParagraphEditorDirective)
  .controller('ParagraphEditorController', ParagraphEditorController);
  

ParagraphEditorController.$inject = ['$scope', 'ModelsManager', '$http', 'Upload', 'JetlagUtils'];

function ParagraphEditorController($scope, ModelsManager, $http, Upload, JetlagUtils) {
  var ctrl = this;
  ctrl.leafletMap = { markers: {} };
  
  var inputTypeList = ['text', 'picture', 'location', 'external']; // XXX: should be moved into a service
  
  var defaultCenter = {
        lat: 45.74,
        lng: 4.87,
        zoom: 8
  }
  
  ctrl.isInputEmpty = function() {
	return _.every(inputTypeList, function(type) {
	  return _.isEmpty(ctrl.model[type]);
    })
  }

  ctrl.changeInputType = function(inputType) {
	if(JetlagUtils.findValue(inputTypeList, inputType) != null) {
		ctrl.model.type = inputType;
		if(inputType === 'location') {
			if(ctrl.model.location == null) {
				ctrl.model.location = {};
			}
			if(ctrl.model.location.markers == null) {
				ctrl.model.location.markers = {};
			}
			if(ctrl.model.location.center == null) {
				ctrl.model.location.center = defaultCenter;
			}
		}
	}
  }

  ctrl.uploadFiles = function(files) {
    if(files && files.length == 1) {
	  ctrl.model.picture = files[0];
    }
  }

  ctrl.pictureSelected = function() {
	return !_.isEmpty(ctrl.model.picture);
  }

  ctrl.changeLocation = function() {
	if(ctrl.model.type === 'location' && ctrl.model.location.name != null) {
	  $http.get('https://search.mapzen.com/v1/search', { params: {
	    text: ctrl.model.location.name,
		api_key: 'search-KjBcCm0'
		}}).success(function(results) {
		  if(_.isObject(results) && results.features.length > 0) {
			var firstMatch = results.features[0];
			ctrl.model.location.name = firstMatch.properties.label;
			ctrl.model.location.coordinates = firstMatch.geometry.coordinates;
		  }
		}).error(function(error) {
		  console.log(error);
		});
	}
  }

  function getLeafletMapMarkerCoordinates() {
    if(ctrl.model && ctrl.model.location) {
	  return ctrl.model.location.coordinates;
    } else {
	  return null;
    }
  }

  $scope.$watch(getLeafletMapMarkerCoordinates, function(newValue, oldValue) {
    if(newValue != null && newValue != oldValue && newValue.length === 2) {
	  ctrl.changeMarkerPosition(newValue);
    }
  });

  ctrl.changeMarkerPosition = function(coordinates) {
    ctrl.model.location.markers.marker = {
	  message: ctrl.model.location.name,
	  lat: coordinates[1],
	  lng: coordinates[0],
	  draggable: true,
	  focus: true
    }
  }

  $scope.$on('leafletDirectiveMarker.bad_id.dragend', function(e, m) {
	ctrl.model.location.markers.marker = m.model;
	var marker = ctrl.model.location.markers.marker;
	  if(marker.lng !== ctrl.model.location.coordinates[0] ||
		 marker.lat !== ctrl.model.location.coordinates[1]) {
		  $http.get('https://search.mapzen.com/v1/reverse', { params: {
			  'point.lat': marker.lat,
			  'point.lon': marker.lng,
			  api_key: 'search-KjBcCm0'
			}}).success(function(results) {
			  if(_.isObject(results) && results.features.length > 0) {
				var firstMatch = results.features[0];
				ctrl.model.location.name = firstMatch.properties.label;
				ctrl.model.location.markers.marker.message = firstMatch.properties.label;
			  }
			}).error(function(error) {
			  console.log(error);
			});
	  }
  });
  
  ctrl.revert = function() {
      if(ctrl.model.type === 'text') {
          ctrl.model.text = ctrl._previous.text;
      } else if(ctrl.model.type === 'picture') {
        ctrl.model.picture = ctrl._previous.picture;
      } else if(ctrl.model.type === 'location') {
        ctrl.model.location = ctrl._previous.location;
      } else if(ctrl.model.type === 'external') {
          ctrl.model.external = ctrl._previous.external;
      }
      ctrl.cancel();
  }
  
}

function JlParagraphEditorDirective() {
  return {
    templateUrl: '/templates/jlParagraphEditor.html',
    scope: {},
	bindToController: {
		model: '=',
		save: '&',
        cancel: '&'
	},
    restrict: 'E',
    controller: 'ParagraphEditorController',
    controllerAs: 'ParagraphEditorCtrl',
    link: function(scope, element, attrs, controller) {
        controller._previous = JSON.parse(JSON.stringify(controller.model));
    }
  }
}