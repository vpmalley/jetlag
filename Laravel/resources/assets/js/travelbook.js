angular
  .module('jetlag.webapp.travelbook', ['jetlag.webapp.app'])
  .controller('TravelbookController', TravelbookController);

TravelbookController.$inject = ['$scope', 'ModelsManager'];

function TravelbookController($scope, ModelsManager) {
	var ctrl = this;
	
	ctrl.travelbooks = new ModelsManager.TravelBookCollection();
	ctrl.travelbooks.fetch();
};

