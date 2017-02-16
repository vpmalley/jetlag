(function(angular) {
'use strict';

    function typeMapper() {
        this.map = function(value, type) {
            if(type === 'datetime') {
                return moment(value);
            } else {
                return value;
            }
        }

        this.unmap = function(value, type) {
            if(type === 'datetime') {
                return value.toISOString();
            } else {
                return value;
            }
        }
    }

    angular
    .module('jetlag.webapp.etc')
    .service('typeMapper', typeMapper);
})(angular);