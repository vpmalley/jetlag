var dependencies = [
  'jetlag.webapp.app', 
  'ngFileUpload',
  'leaflet-directive',
  'monospaced.elastic'
];

angular
  .module('jetlag.webapp.travelbookCreator', dependencies)
  .controller('TravelbookCreatorController', TravelbookCreatorController)
  .controller('ArticleCreatorController', ArticleCreatorController)
  .filter('paragraphText', ParagraphTextFilter);

TravelbookCreatorController.$inject = ['$scope', 'ModelsManager'];
ArticleCreatorController.$inject = ['$scope', 'ModelsManager', '$http', 'Upload', '$sce'];
ParagraphTextFilter.$inject = ['$sce'];

function TravelbookCreatorController($scope, ModelsManager) {
	var ctrl = this;
	ctrl.tbStep = 1;
	
	ctrl.changeTbStep = function(stepNumber) {
		if(_.isNumber(stepNumber)) {
			ctrl.tbStep = stepNumber;
		} else {
			if(ctrl.tbStep < 5) {
				ctrl.tbStep += 1;
			} else {
				console.log('end of process!');
			}
		}
	}
	
	ctrl.toggleTbStep = function(stepNumber) {
		if(ctrl.tbStep != stepNumber) {
			ctrl.changeTbStep(stepNumber);
		} else {
			ctrl.changeTbStep();
		}
	}
};

function ArticleCreatorController($scope, ModelsManager, $http, Upload, $sce) {
	var ctrl = this;
	ctrl.articleStep = 0;
	ctrl.paragraphEditor = { input: {type: 'text', text: '', picture: {}, location: {}, external: {}}};
	ctrl.leafletMap = { markers: {} };
	ctrl.paragraphs = [];
	
	ctrl.changeArticleStep = function(stepNumber) {
		if(_.isNumber(stepNumber)) {
			ctrl.articleStep = stepNumber;
		} else {
			if(ctrl.articleStep < 2) {
				ctrl.articleStep += 1;
			} else {
				console.log('end of process!');
			}
		}
	}
	
	ctrl.toggleArticleStep = function(stepNumber) {
		if(ctrl.articleStep != stepNumber) {
			ctrl.changeArticleStep(stepNumber);
		} else {
			ctrl.changeArticleStep();
		}
	}
	
	var inputTypeList = ['text', 'picture', 'location', 'external']; // XXX: should be moved into a service
	function findValue(collection, value) { // XXX: should be moved into a utils service
		return _.find(collection, function(item) {
			return item === value;
			});
	}
	
	ctrl.changeInputType = function(inputType) {
		if(findValue(inputTypeList, inputType) != null) {
			ctrl.paragraphEditor.input.type = inputType;
		}
	}
	
	ctrl.uploadFiles = function(files) {
		console.log(files);
	}
	
	ctrl.changeLocation = function() {
		if(ctrl.paragraphEditor.input.type === 'location' && ctrl.paragraphEditor.input.location.name != null) {
			$http.get('https://search.mapzen.com/v1/search', { params: {
			  text: ctrl.paragraphEditor.input.location.name,
			  api_key: 'search-KjBcCm0'
			}}).success(function(results) {
			  if(_.isObject(results) && results.features.length > 0) {
			    var firstMatch = results.features[0];
				ctrl.paragraphEditor.input.location = {
				  name: firstMatch.properties.label,
				  coordinates: firstMatch.geometry.coordinates
				};
			  }
			}).error(function(error) {
			  console.log(error);
			});
		}
	}
	function getLeafletMapMarkerCoordinates() {
	  if(ctrl.paragraphEditor.input &&
	     ctrl.paragraphEditor.input.location) {
	    return ctrl.paragraphEditor.input.location.coordinates;
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
	  ctrl.leafletMap.markers.marker = {
	    message: ctrl.paragraphEditor.input.location.name,
		lat: coordinates[1],
		lng: coordinates[0],
		draggable: true,
		focus: true
	  }
	}
	
	$scope.$on('leafletDirectiveMarker.dragend', function(e, m) {
		ctrl.leafletMap.markers.marker = m.model;
		var marker = ctrl.leafletMap.markers.marker;
		  if(marker.lng !== ctrl.paragraphEditor.input.location.coordinates[0] ||
			 marker.lat !== ctrl.paragraphEditor.input.location.coordinates[1]) {
			  $http.get('https://search.mapzen.com/v1/reverse', { params: {
				  'point.lat': marker.lat,
				  'point.lon': marker.lng,
				  api_key: 'search-KjBcCm0'
				}}).success(function(results) {
				  if(_.isObject(results) && results.features.length > 0) {
					var firstMatch = results.features[0];
					ctrl.paragraphEditor.input.location.name = firstMatch.properties.label;
					ctrl.leafletMap.markers.marker.message = firstMatch.properties.label;
				  }
				}).error(function(error) {
				  console.log(error);
				});
			 }
	});
	
	/* XXX: Many functions and code relative to Leaflet should go to a Leaflet service */
	ctrl.leafletMap.center = {
        lat: 51.505,
        lng: -0.09,
        zoom: 8
    }
	
	/* Transform what's in the paragraphEditor.input into a proper paragraph ready to be displayed */
	ctrl.addParagraph = function() {
	  var input = ctrl.paragraphEditor.input;
	  switch(input.type) {
	  case 'text':
	  ctrl.paragraphs[ctrl.paragraphs.length] = {type: 'text', text: input.text};
	    break;
	  case 'picture':
	    break;
	  case 'location':
	  ctrl.paragraphs[ctrl.paragraphs.length] = {type: 'location', location: { name: input.location.name, markers: {marker: {
	    message: input.location.name,
		lat: input.location.coordinates[1],
		lng: input.location.coordinates[0],
		draggable: false,
		focus: true
	  }}, center: angular.copy(ctrl.leafletMap.center)
	  }};
	    break;
	  case 'external':
	  ctrl.paragraphs[ctrl.paragraphs.length] = {type: 'external', link: input.external.link}
	    break;
	  default:break;
	  }
	  /* reset the paragraphEditor input */
	  ctrl.paragraphEditor.input= {type: 'text', text: '', picture: {}, location: {}, external: {}};
	}
};

function ParagraphTextFilter($sce) { // My custom filter
  return function (input) {
    var formatedInput = input.replace(new RegExp('\n', 'g'), '<br>');
    return $sce.trustAsHtml(formatedInput);
  }
};
