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
	ctrl.travelbook = new ModelsManager.Travelbook();
	
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
	
	ctrl.saveTravelbook = function() {
		if(ctrl.travelbook) {
			ctrl.travelbook.save()
			.success(function(data) {
				if(data.id) {
					window.location.href = '/travelbook/#/' + data.id;
				}
			});
		}
	}
	
	ctrl.createArticle = function() {
		if(ctrl.travelbook) {
			ctrl.travelbook.save()
			.success(function(data) {
				if(data.id) {
					var article = new ModelsManager.Article();
					article.set('travelbook', ctrl.travelbook);
					article.save()
					.success(function(data) {
						if(data.id) {
							window.location.href = '/article/create/#/' + data.id;
						}
					})
				}
			})
		}
	}
};
