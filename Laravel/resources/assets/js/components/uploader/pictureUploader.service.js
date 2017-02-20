(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.uploader')
  .service('pictureUploaderService', pictureUploaderService);

  pictureUploaderService.$inject = ['uploaderService', 'paragraphsService'];

  function pictureUploaderService(uploaderService, paragraphsService) {
    var UPLOAD_URL = '/api/0.1/pix/upload';

    this.upload = function(data) {
        return uploaderService.upload(UPLOAD_URL, data).then(function(result) {
            return paragraphsService.mapPicture(result);
        }, function(error) {
            return error;
        });
    }
  }

})(angular);