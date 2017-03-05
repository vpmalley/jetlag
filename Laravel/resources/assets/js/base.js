(function(angular) {
    'use strict';

function JetlagUtils() {
	/* Based on Typescript behaviour */
	function makeEnum(enumValues) {
	    var enumObject;
        (function (enumObject) {
            var index = 0;
            enumValues.forEach(function(value) {
                enumObject[enumObject[value] = index++] = value;
            });
        })(enumObject || (enumObject = {}));
        return enumObject;
	}

	return {
		makeEnum: makeEnum
	}
}

function momentToString() {
    return function(input) {
        /* XXX: temp hack cause moment-picker has strange behaviour
        * cf https://github.com/indrimuska/angular-moment-picker/issues/60
        */
        return moment.isMoment(input) ? input.format("DD.MM.YYYY") : input;
    }
}

angular
  .module('jetlag.webapp.base', [])
  .factory('JetlagUtils', JetlagUtils)
  .filter('momentToString', momentToString);
  
})(window.angular);