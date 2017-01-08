(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.uploader')
  .service('uploaderService', uploaderService);

uploaderService.$inject = ['Upload', '$q'];

function uploaderService(Upload, $q) {

    this.upload = function(url, data) {
        var q = $q.defer();

        Upload.upload({
            url: url,
            data: data
        }).then(function (result) {
            q.resolve(result.data);
        }, function (resp) {
            console.warn('Unable to upload file', resp);
            q.reject(resp);
        });

        return q.promise;
    }
}

})(window.angular);

