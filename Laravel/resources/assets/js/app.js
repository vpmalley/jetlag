angular
  .module('jetlag.webapp.app', ['jetlag.webapp.base'])
  .controller('AppController', AppController);
  
  AppController.$inject = ['$location'];

function AppController($location) {

  var ctrl = this;
  ctrl.leftMenuOpen = false;
  ctrl.rightMenuOpen = false;
  ctrl.redirectTo = window.location.pathname+window.location.search;
  
  ctrl.openLeftMenu = function() {
	ctrl.rightMenuOpen = false;
	ctrl.leftMenuOpen = true;
  }
  
  ctrl.closeLeftMenu = function() {
	ctrl.leftMenuOpen = false;
  }
  
  ctrl.openRightMenu = function() {
	ctrl.leftMenuOpen = false;
	ctrl.rightMenuOpen = true;
  }
  
  ctrl.closeRightMenu = function() {
	ctrl.rightMenuOpen = false;
  }
}