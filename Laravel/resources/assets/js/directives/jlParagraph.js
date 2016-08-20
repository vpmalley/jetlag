var dependencies = [
  'leaflet-directive',
  'jetlag.webapp.directives.paragraphEditor'
];

angular
  .module('jetlag.webapp.directives.paragraph', dependencies)
  .directive('jlParagraph', JlParagraphDirective)
  .controller('ParagraphController', ParagraphController)
  .filter('paragraphText', ParagraphTextFilter);

ParagraphController.$inject = ['$scope'];  
ParagraphTextFilter.$inject = ['$sce'];

function ParagraphController($scope) {
  var ctrl = this;
  
  $scope.$watch(function(){return ctrl.isBeingEdited()}, function(newValue, oldValue) {
	 if((newValue === true || newValue === false) && newValue !== oldValue) {
		 if(ctrl.model.type === 'location') {
			 var location = ctrl.model.location;
			 if(location.markers && location.markers.marker) {
				 location.markers.marker.draggable = newValue;
			 }
		 }
	 } 
  });
}

function ParagraphTextFilter($sce) { // My custom filter
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
    controllerAs: 'ParagraphCtrl'
  }
}