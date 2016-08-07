(function(angular) {
    
angular
  .module('jetlag.webapp.travelbook', ['jetlag.webapp.app'])
  .controller('TravelbookController', TravelbookController);

TravelbookController.$inject = ['$scope', 'ModelsManager', '$location'];

function TravelbookController($scope, ModelsManager, $location) {
	var ctrl = this;
	
	ctrl.travelbooks = new ModelsManager.TravelbookCollection();
	ctrl.travelbooks.fetch();
    ctrl.location = $location;
    ctrl.currentArticle = null;
    
    ctrl.navigateTo = function(article) {
        if(ctrl.travelbook !== null && article !== null) {
            window.location.hash = article.id;
            ctrl.currentArticle = article;
        }
    }
    
    ctrl.hasPreviousArticle = function() {
        var ret = false;
        if(ctrl.travelbook != null && ctrl.currentArticle !== null) {
            ctrl.travelbook.articles.some(function(article, idx) {
               if(article.id === ctrl.currentArticle.id) {
                   ret = idx > 0;
                   return true;
               }
               return false;
            });
        }
        return ret;
    }
    
    ctrl.hasNextArticle = function() {
        var ret = false;
        if(ctrl.travelbook != null && ctrl.currentArticle !== null) {
            ctrl.travelbook.articles.some(function(article, idx) {
               if(article.id === ctrl.currentArticle.id) {
                   ret = ctrl.travelbook.articles.length > (idx + 1);
                   return true;
               }
               return false;
            });
        }
        return ret;
    }
    
    ctrl.travelbook = {
        id: 1,
        title: "Aller-retour d'un nain en Irlande",
        begin_date: moment("2016-03-27", "YYYY-MM-DD"),
        end_date: moment("2016-04-02", "YYYY-MM-DD"),
        descriptionPicture: {
            url: "4.jpg"
        },
        descriptionText: "Au pays de la Guinness, des bus à deux étages, du hurling, des trèfles à quatre feuilles" + 
		  "et des paysages qui en mettent plein la vue !",
        location: {
          label: "Dublin, Ireland"  
        },
        articles: [{
            id: 1,
            title: "Landing in Dublin",
            paragraphs: [],
            date: moment("2016-03-27", "YYYY-MM-DD"),
            location: {
                label: "Dublin, Ireland"
            },
            is_draft: false,
            is_public: true,
            travelbook: {
                id: 1
            }
          },
          {
            id: 2,
            title: "Going West to the Arran Islands",
            paragraphs: [],
            date: moment("2016-03-29", "YYYY-MM-DD"),
            location: {
                label: "Arran Islands, Co. Galway, Ireland"
            },
            is_draft: false,
            is_public: true,
            travelbook: {
                id: 1
            }
          },
          {
            id: 3,
            title: "The ring of Kerry",
            paragraphs: [],
            date: moment("2016-04-02", "YYYY-MM-DD"),
            location: {
                label: "Co. Kerry, Ireland"
            },
            is_draft: false,
            is_public: true,
            travelbook: {
                id: 1
            }
          },
          {
            id: 4,
            title: "Finishing with a pubcrawl",
            paragraphs: [],
            date: moment("2016-04-02", "YYYY-MM-DD"),
            location: {
                label: "Dublin, Co. Dublin, Ireland"
            },
            is_draft: false,
            is_public: true,
            travelbook: {
                id: 1
            }
          }
        ],
        is_draft: false,
        is_public: true  
    };
};

})(angular);

