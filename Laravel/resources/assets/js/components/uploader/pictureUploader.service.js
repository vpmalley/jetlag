(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.uploader')
  .service('pictureUploaderService', pictureUploaderService);

  pictureUploaderService.$inject = ['uploaderService'];

  function pictureUploaderService(uploaderService) {
    var UPLOAD_URL = '/api/0.1/pix/upload';

    this.upload = function(data) {
        return uploaderService.upload(UPLOAD_URL, data);
    }
  }

})(angular);