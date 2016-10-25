angular
  .module('jetlag.webapp.app', ['jetlag.webapp.base'])
  .config(AppConfig)
  .controller('AppController', AppController);
  
  AppController.$inject = ['$location', '$scope'];
  AppConfig.$inject = ['$locationProvider'];
  
function AppConfig($locationProvider) {
 //   $locationProvider.html5Mode(false).hashPrefix('');
}

function AppController($location, $scope) {

  var ctrl = this;
  ctrl.leftMenuOpen = false;
  ctrl.rightMenuOpen = false;
  ctrl.redirectTo = window.location.pathname+window.location.search;
  
  $scope.location = $location;
  
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