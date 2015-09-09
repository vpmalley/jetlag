angular
  .module('jetlag', [])
  .controller('AppController', AppController);
  
  AppController.$inject = ['$scope'];
  
function AppController($scope) {
  var ctrl = this;
  ctrl.crazyMessage = "First Angular message !";
}