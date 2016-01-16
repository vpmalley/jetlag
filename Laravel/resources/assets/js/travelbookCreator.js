angular
  .module('jetlag.webapp.travelbookCreator', ['jetlag.webapp.app', 'ngFileUpload'])
  .controller('TravelbookCreatorController', TravelbookCreatorController)
  .controller('ArticleCreatorController', ArticleCreatorController);

TravelbookCreatorController.$inject = ['$scope', 'ModelsManager'];
ArticleCreatorController.$inject = ['$scope', 'ModelsManager', 'Upload'];

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

function ArticleCreatorController($scope, ModelsManager, Upload) {
	var ctrl = this;
	ctrl.articleStep = 0;
	
	ctrl.changeArticleStep = function(stepNumber) {
		if(_.isNumber(stepNumber)) {
			ctrl.articleStep = stepNumber;
		} else {
			if(ctrl.articleStep < 5) {
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
};

