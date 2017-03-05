angular
  .module('jetlag.webapp.user', ['jetlag.webapp.app'])
  .controller('UserController', UserController);

UserController.$inject = ['$scope', 'JLModelsManager'];

function UserController($scope, JLModelsManager) {
	var ctrl = this;
	ctrl.changePasswordChecked = false;
    ctrl.articles = [];
    JLModelsManager.Article.fetchCollection().then(function(collection) {
        ctrl.articles = collection;
    });

    ctrl.changePasswordCheckedChanged = function() {
        if(!ctrl.changePasswordChecked) {
            ctrl.newPassword = null;
            ctrl.oldPassword = null;
            ctrl.confirmOldPassword = null;
        }
    }

    ctrl.isPrivateDetailsFormValid = function() {
        var valid = true;

        if(ctrl.changePasswordChecked) {
            if(ctrl.newPassword == null
            || ctrl.oldPassword == null || ctrl.oldPassword !== ctrl.confirmOldPassword) {
                valid = false;
            }
        }

        return valid && false; // XXX: form is not active for now
    }
};

