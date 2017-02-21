var dependencies = [
  'jetlag.webapp.app', 
  'ngFileUpload',
  'leaflet-directive',
  'monospaced.elastic',
  'jetlag.webapp.directives.paragraph',
  'jetlag.webapp.components.geocoding',
  'jetlag.webapp.components.uploader',
  'jetlag.webapp.components.paragraphs'
];

angular
  .module('jetlag.webapp.articleCreator', dependencies)
  .controller('ArticleCreatorController', ArticleCreatorController);

ArticleCreatorController.$inject = ['$scope', 'ModelsManager', '$http', 'pictureUploaderService',
    'JetlagUtils', '$location', 'JLModelsManager', 'geocodingService', 'paragraphsService'];

function ArticleCreatorController($scope, ModelsManager, $http, pictureUploaderService,
    JetlagUtils, $location, JLModelsManager, geocodingService, paragraphsService) {
	var ctrl = this;
	ctrl.paragraphEditor = {
	    blockContentType: paragraphsService.contentTypes.TEXT,
	    blockContent: undefined
	};
	ctrl.paragraphs = [];
	ctrl.article = null;
	ctrl.mm = ModelsManager;
	ctrl.editedParagraph = null;
	ctrl.articleLoaded = false;
	
	function loadArticle(articleID) {
	    ctrl.articleLoaded = false;
		ctrl.article = new JLModelsManager.Article();
		ctrl.article.id = articleID;
		ctrl.article.fetch().then(function() {
		    ctrl.articleLoaded = true;
		});
	}

	if(preload.articleId != null) {
	    loadArticle(preload.articleId);
	}

	/* Transform what's in the paragraphEditor.input into a proper paragraph ready to be displayed */
	ctrl.addParagraph = function() {
	  ctrl.article.paragraphs.push(ctrl.paragraphEditor);
	  /* reset the paragraphEditor input */
	  ctrl.resetParagraphEditor();

	  // TODO: handle auto save during creation
	  //ctrl.article.save({paragraphs: ctrl.article.paragraphs}, {patch: true}); //XXX: PATCH REQUEST
	}
	
	ctrl.resetParagraphEditor = function() {
	  ctrl.paragraphEditor = {
        blockContentType: paragraphsService.contentTypes.TEXT,
        blockContent: {
            value: null
        }
	  };
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