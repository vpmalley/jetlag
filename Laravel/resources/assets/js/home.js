angular
  .module('jetlag.webapp.home', ['jetlag.webapp.base'])
  .controller('AppController', AppController);

AppController.$inject = ['$scope', 'ModelsManager'];

function AppController($scope, ModelsManager) {

  var ctrl = this;
  ctrl.firstArticle = new ModelsManager.Article();
  ctrl.firstUSer = new ModelsManager.User();
  ctrl.firstTravelBook = new ModelsManager.TravelBook();
}