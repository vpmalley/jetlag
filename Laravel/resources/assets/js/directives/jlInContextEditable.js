var dependencies = [
];

angular
  .module('jetlag.webapp.directives.inContextEditable', dependencies)
  .controller('InContextEditableController', InContextEditableController)
  .directive('jlInContextEditable', InContextEditableDirective);

function InContextEditableController($scope) {
    
    var ctrl = this;
    $scope.ctrl = ctrl;
    ctrl.element = null;
    ctrl.hidden = null;
    ctrl.visible = null;
    ctrl.isActive = false;
    ctrl.hiddenClickListener = null;
    ctrl.visibleClickListener = null;
    ctrl.documentClickListener = null;
    ctrl.cancel = null;
    ctrl.valid = null;
    
    ctrl.valid = function() {
        ctrl.stopEditing();
        ctrl.validEdit();
    }

    ctrl.cancel = function() {
        ctrl.stopEditing();
        ctrl.cancelEdit();
    }

    ctrl.stopEditing = function() {
        ctrl.element.removeClass('editing');
        ctrl.visible.hide();
        ctrl.visible.siblings('i.fa-check, i.fa-times').hide();
        ctrl.hidden.show();
        ctrl.hidden.siblings('i.fa-pencil').show();
        $(document).off('click');
    }

    ctrl.documentClickListener = function(e) {
        ctrl.cancel();
        e.stopPropagation();
    }
    
    ctrl.hiddenClickListener = function(e) {
        ctrl.element.addClass('editing');
        ctrl.hidden.hide();
        ctrl.hidden.siblings('i.fa-pencil').hide();
        ctrl.visible.show();
        ctrl.visible.siblings('i.fa-times, i.fa-check').show();
        $(window.document).click(ctrl.documentClickListener);
        e.stopPropagation();
    }
    ctrl.visibleClickListener = function(e) {
        e.stopPropagation();
    }
    
    var elementWatcher = $scope.$watch('element', function(newValue, oldValue) {
        if(newValue !== undefined) {
            ctrl.element = $(newValue);
            ctrl.hidden = ctrl.element.find('.editing-hidden');
            ctrl.visible = ctrl.element.find('.editing-visible');
            ctrl.element.addClass('in-context-editable');
            elementWatcher();
        }
    });
    
    $scope.$watch(function() {return ctrl.active();}, function(newValue, oldValue) {
        if(newValue === true) {
            ctrl.isActive = true;
            ctrl.addControlsAndListeners();
        } else {
            ctrl.isActive = false;
            ctrl.cancel();
            ctrl.removeControlsAndListeners();
        }
    });
    
    ctrl.addControlsAndListeners = function() {
        if(ctrl.element === null || ctrl.hidden === null || ctrl.visible === null)
            return;
        
        /* Add controls */
        
        ctrl.hidden.after(function() {
            /*if($(this).css("display") === "inline" || $(this).css("display") === "inline-block") {
                return "<i class=\"fa fa-fw fa-pencil\"></i>";
            } else {
                console.warn("The '.editing-hidden' class must be used on inline elements only inside in-context-editable directve");
                return null;
            }*/
            return "<i class=\"fa fa-fw fa-pencil\"></i>";
        });
    
        ctrl.visible.after(function() {
           /*if($(this).css('display') === 'inline' || $(this).css('display') === 'inline-block') {
               return "<i class=\"fa fa-fw fa-check\" ng-click=\"valid()\"></i><i class=\"fa fa-fw fa-times\" ng-click=\"cancel()\"></i>";     
           } else {
               console.warn("The '.editing-visible' class must be used on inline elements only inside in-context-editable directve");
               return null;
           }*/
           return "<i class=\"fa fa-fw fa-check\"></i><i class=\"fa fa-fw fa-times\"></i>";
        });
        ctrl.visible.siblings("i.fa-check, i.fa-times").hide();
    
        /* Add click listeners */
        ctrl.hidden.click(ctrl.hiddenClickListener);
        ctrl.hidden.siblings("i.fa-pencil").click(ctrl.hiddenClickListener);
        ctrl.visible.click(ctrl.visibleClickListener);
        ctrl.visible.siblings("i.fa-check").click(ctrl.valid);
        ctrl.visible.siblings("i.fa-times").click(ctrl.cancel);
    }
    
    ctrl.removeControlsAndListeners = function() {
        if(ctrl.element === null || ctrl.hidden === null || ctrl.visible === null)
            return;
        
        /* Remove controls and delete their click listeners*/
        ctrl.hidden.siblings("i.fa-pencil").off("click", null, ctrl.hiddenClickListener).remove();
        ctrl.visible.siblings("i.fa-check").off("click", null, ctrl.valid).remove();
        ctrl.visible.siblings("i.fa-times").off("click", null, ctrl.cancel).remove();
        
        /* Remove click listeners */
        ctrl.hidden.off('click', null, ctrl.hiddenClickListener);
        ctrl.visible.off('click', null, ctrl.visibleClickListener);
        $(window.document).off('click', null, ctrl.documentClickListener);
    }
}

InContextEditableController.$inject = ['$scope'];

function InContextEditableDirective() {
  return {
    template: '<ng-transclude></ng-transclude>',
    bindToController: {
		active: '&',
        cancelEdit: '&',
        validEdit: '&'
	},
    scope: {},
    link: function($scope, element, attrs, controller, transclude) {
        $scope.element = element;
    },
    transclude: true,
    controller: 'InContextEditableController',
    controllerAs: 'InContextEditableCtrl',
    restrict: 'AE'
  }
}