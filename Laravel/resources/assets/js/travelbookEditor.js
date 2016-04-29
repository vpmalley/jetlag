var dependencies = [
  'jetlag.webapp.app',
  'jetlag.webapp.directives.paragraph'
];

angular
  .module('jetlag.webapp.travelbookEditor', dependencies)
  .controller('TravelbookEditorController', TravelbookEditorController);

TravelbookEditorController.$inject = ['$scope', 'ModelsManager'];

function TravelbookEditorController($scope, ModelsManager) {
	var ctrl = this;
	ctrl.travelbook = new ModelsManager.Travelbook();
	ctrl.travelbook.get('articles').push({"id":1,"title":"La blague de Patochee","descriptionText":"","descriptionPicture":null,"is_draft":true,"is_public":false,"paragraphs":[{"type":"text","text":"Ceci est le tout début de mes aventures en Irlande you mothafucka !","$$hashKey":"object:15"},{"type":"text","text":"AHAHAHA trop marrant !","$$hashKey":"object:22"},{"type":"location","location":{"name":"Dublin, Ireland","markers":{"marker":{"message":"Dublin, Ireland","lat":53.336022,"lng":-6.27918,"draggable":false}},"center":{"lat":53.45861991140519,"lng":-5.95458984375,"zoom":8,"autoDiscover":false},"coordinates":[-6.27918,53.336022]},"$$hashKey":"object:36"}],"authorUsers":{"1":"owner"},"travelbook":null,"url":"http://homestead.app/article/1","descriptionMedia":{"id":1,"smallUrl":"https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1","mediumUrl":"https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1","bigUrl":"https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1"},"isDraft":1,"isPublic":0});
	
	ctrl.getFirstArticleParagraphs = function() {
		if(ctrl.travelbook != null) {
			if(ctrl.travelbook.$attributes.articles.length > 0) {
				var firstArticle = ctrl.travelbook.$attributes.articles[0];
				return firstArticle.paragraphs ? firstArticle.paragraphs : [];
			}
		}
		return [];
	}
}