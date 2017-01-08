var dependencies = [
  'jetlag.webapp.app', 
  'ngFileUpload',
  'leaflet-directive',
  'monospaced.elastic',
  'jetlag.webapp.directives.paragraph',
  'jetlag.webapp.components.geocoding',
  'jetlag.webapp.components.uploader'
];

angular
  .module('jetlag.webapp.articleCreator', dependencies)
  .controller('ArticleCreatorController', ArticleCreatorController);

ArticleCreatorController.$inject = ['$scope', 'ModelsManager', '$http', 'pictureUploaderService',
    'JetlagUtils', '$location', 'JLModelsManager', 'geocodingService'];

function ArticleCreatorController($scope, ModelsManager, $http, pictureUploaderService,
    JetlagUtils, $location, JLModelsManager, geocodingService) {
	var ctrl = this;
	ctrl.paragraphEditor = { input: {type: 'text', text: '', picture: {}, location: {}, external: {}}};
	ctrl.paragraphs = [];
	ctrl.article = null;
	ctrl.mm = ModelsManager;
	ctrl.editedParagraph = null;
	ctrl.articleLoaded = false;
	
	function getArticleID() {
      var path = $location.path();

	  if(path !== '') {
	    var id = parseInt(path.substring(1));

		if(!isNaN(id)) {
			return id;
		} else {
			return null;
		}
	  }	
	};
	
	function loadArticle(articleID) {
	    ctrl.articleLoaded = false;
		ctrl.article = new JLModelsManager.Article();
		ctrl.article.id = articleID;
		ctrl.article.fetch().then(function() {
		    ctrl.articleLoaded = true;
		});
	}
	
	/* This wait for the article to be loaded.
	* XXX: need to handle if there are pending changes and user changes the URL
	*/
	$scope.$watch(getArticleID, function(newValue, oldValue){
		if(newValue != null) {
			if(ctrl.article != null && ctrl.article.id !== newValue) {
				if(ctrl.article.hasChanged()) {
					ctrl.article.save()
					.then(function() {
						loadArticle(newValue);
					}, function() {
						console.error('Unable to save changes of current article');
					});
				} else {
					loadArticle(newValue);
				}
			} else {
				loadArticle(newValue);
			}
		}
	});
	
	/* Transform what's in the paragraphEditor.input into a proper paragraph ready to be displayed */
	ctrl.addParagraph = function() {
	  var input = ctrl.paragraphEditor.input;
	  var paragraph = null;
	  switch(input.type) {
          case 'text':
          paragraph = _.pick(input, ['type', 'text']);
            break;
          case 'picture':
          paragraph = _.pick(input, ['type', 'picture']);
          //paragraph = {type: 'picture', pictures: [input.picture].concat([])}; //XXX: magical hack here to deeply copy
            break;
          case 'location':
          paragraph = {
            type: 'location',
            location: {
                name: input.location.name,
                markers: {
                    marker: {
                        message: input.location.name,
                        lat: input.location.coordinates[1],
                        lng: input.location.coordinates[0],
                        draggable: false
                    }
                },
                center: angular.copy(ctrl.leafletMap.center),
                coordinates: angular.copy(input.location.coordinates)
            }
          };
            break;
          case 'external':
          paragraph = _.pick(input, ['type', 'external']);
            break;
          default:break;
	  }
	  /* add it to the article */
	  ctrl.article.paragraphs.push(paragraph);
	  /* reset the paragraphEditor input */
	  ctrl.resetParagraph();
	  ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
	}
	
	ctrl.resetParagraph = function() {
	  /* reset the paragraphEditor input */
	  ctrl.paragraphEditor.input = {type: 'text', text: '', picture: {}, location: {}, external: {}};
	}
	
	function swapParagraphs(index1, index2) {
	 var paragraph = ctrl.article.paragraphs[index1];

	 if(paragraph != null) {
	   ctrl.article.paragraphs[index1] = ctrl.article.paragraphs[index2];
	   ctrl.article.paragraphs[index2] = paragraph;
	 }
	}
	
	ctrl.paragraphUp = function(index) {
	  if(!ctrl.article) {
	    console.error('Missing article');
		return;
	  }
	  if(index < 1 || index >= ctrl.article.paragraphs.length) {
	    console.error('Wrong paragraph index');
		return;
      }
	  swapParagraphs(index-1, index);
	  ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
	}

	ctrl.paragraphDown = function(index) {
	  if(!ctrl.article) {
	    console.error('Missing article');
		return;
	  }
	  if(index < 0 || index >= ctrl.article.$attributes.paragraphs.length - 1) {
	    console.error('Wrong paragraph index');
		return;
      }
	  swapParagraphs(index, index+1);
	  ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
	}
	ctrl.removeParagraph = function(index) {
	  if(!ctrl.article) {
	    console.error('Missing article');
		return;
	  }
	  if(index < 0 || index > ctrl.article.paragraphs.length - 1) {
	    console.error('Wrong paragraph index');
		return;
      }
	  ctrl.article.paragraphs.splice(index, 1);
	  ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
	}
	
	ctrl.editParagraph = function(index) {
	  ctrl.editedParagraph = index;
	}
	
	ctrl.saveParagraph = function(index) {
		if(ctrl.isBeingEdited(index)) {
			ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
			ctrl.editedParagraph = null;
		}
	}
    
    ctrl.cancelParagraphEdition = function() {
        ctrl.editedParagraph = null;
    }
	
	ctrl.isBeingEdited = function(index) {
		return ctrl.editedParagraph === index;
	}
	
	ctrl.loseArticle = function() {
		ctrl.article.delete();
	}
	
	ctrl.saveOnlyArticle = function() {
		ctrl.article.save()
		.error(function(errors) {
			ctrl.errors = errors.title;
		});
	}
	
	ctrl.publishArticle = function() {
		ctrl.article.isDraft = false;
		ctrl.article.save()
		.error(function(errors) {
			ctrl.errors = errors.title;
		});
	}
	
	ctrl.dismissErrors = function() {
		ctrl.errors = null;
	}
};