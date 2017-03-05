(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.map')
  .service('mapUidService', mapUidService);

function mapUidService() {
    var uids = {};

    this.generateUid = function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
              .toString(16)
              .substring(1);
        }

        var uid;

        while(uid === undefined || uids[uid]) {
            uid = s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
        }

        uids[uid] = true;
        return uid;
    }
}

})(window.angular);