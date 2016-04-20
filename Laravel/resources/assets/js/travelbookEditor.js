var dependencies = [
  'jetlag.webapp.app'
];

angular
  .module('jetlag.webapp.travelbookEditor', dependencies)
  .controller('TravelbookEditorController', TravelbookEditorController);

TravelbookEditorController.$inject = ['$scope', 'ModelsManager'];

function TravelbookEditorController($scope, ModelsManager) {
}