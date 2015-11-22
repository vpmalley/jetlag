angular
  .module('jetlag.webapp.home', ['jetlag.webapp.base'])
  .controller('AppController', AppController);

AppController.$inject = ['$scope', 'ModelsManager'];

function AppController($scope, ModelsManager) {

  var ctrl = this;
  ctrl.firstArticle = new ModelsManager.Article();
  ctrl.firstUSer = new ModelsManager.User();
  ctrl.firstTravelBook = new ModelsManager.TravelBook();
  
  ctrl.openLeftSideBar = function() {
	ctrl.closeRightSideBar();
	$('.side-bar-left:first').show();
  }
  
  ctrl.closeLeftSideBar = function() {
	$('.side-bar-left:first').hide();
  }
  
  ctrl.openRightSideBar = function() {
	ctrl.closeLeftSideBar();
	$('.side-bar-right:first').show();
  }
  
  ctrl.closeRightSideBar = function() {
	$('.side-bar-right:first').hide();
  }
  
  ctrl.isLeftSideBarOpen = function() {
	return $('.side-bar-left:first').is(':visible');
  }
  
  ctrl.isRightSideBarOpen = function() {
	return $('.side-bar-right:first').is(':visible');
  }
}