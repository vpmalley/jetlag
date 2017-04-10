angular
  .module('jetlag.webapp.app', ['jetlag.webapp.base', 'jetlag.webapp.etc'])
  .config(AppConfig)
  .controller('AppController', AppController);
  
  AppController.$inject = ['$interval', '$scope', '$http'];
  AppConfig.$inject = ['$locationProvider'];
  
function AppConfig($locationProvider) {
 //   $locationProvider.html5Mode(false).hashPrefix('');
}

function AppController($interval, $scope, $http) {

  var ctrl = this;
  ctrl.leftMenuOpen = false;
  ctrl.rightMenuOpen = false;
  ctrl.isHeaderMinimized = false;
  ctrl.redirectTo = window.location.pathname+window.location.search;
  $scope.isUserConnected = false;
  
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

      // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('header').outerHeight();

    $(window).scroll(function(event){
        didScroll = true;
    });

    $interval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();

        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;

        // If they scrolled down and are past the navbar, add class .nav-up.
        // This is necessary so you never see what is "behind" the navbar.
        if (st > lastScrollTop && st > navbarHeight){
            // Scroll Down
            ctrl.isHeaderMinimized = true;
        } else if(st + $(window).height() < $(document).height()) {
            ctrl.isHeaderMinimized = false;
        }

        lastScrollTop = st;
    }

    $http.get('/auth/status').then(function(result) {
        $scope.isUserConnected = result.data.connected;
    }, function(error) {
        console.error(error);
    });
}