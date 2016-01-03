angular
  .module('jetlag.webapp.travelbookCreator', ['jetlag.webapp.app'])
  .controller('TravelbookCreatorController', TravelbookCreatorController);

TravelbookCreatorController.$inject = ['$scope', 'ModelsManager'];

function TravelbookCreatorController($scope, ModelsManager) {
	var ctrl = this;
	ctrl.step = 1;
	
	ctrl.changeStep = function(stepNumber) {
		if(_.isNumber(stepNumber)) {
			ctrl.step = stepNumber;
		} else {
			if(ctrl.step < 5) {
				ctrl.step += 1;
			} else {
				console.log('end of process!');
			}
		}
	}
};

