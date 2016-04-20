var dependencies = [
  'jetlag.webapp.app'
];

angular
  .module('jetlag.webapp.articleEditor', dependencies)
  .controller('ArticleEditorController', ArticleEditorController);

ArticleEditorController.$inject = ['$scope', 'ModelsManager'];

function ArticleEditorController($scope, ModelsManager) {
}