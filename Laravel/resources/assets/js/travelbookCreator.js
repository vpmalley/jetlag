var dependencies = [
  'jetlag.webapp.app'
];

angular
  .module('jetlag.webapp.travelbookCreator', dependencies)
  .controller('TravelbookCreatorController', TravelbookCreatorController);

TravelbookCreatorController.$inject = ['$scope', 'ModelsManager'];

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
