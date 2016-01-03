angular
  .module('jetlag.webapp.user', ['jetlag.webapp.app'])
  .controller('UserController', UserController);

UserController.$inject = ['$scope', 'ModelsManager'];

function UserController($scope, ModelsManager) {
	var ctrl = this;
};

