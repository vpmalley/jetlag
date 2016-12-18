(function(angular) {
    'use strict';
    
var modelsDefinition = {
    Article: {
        id: {
          remote: 'id',
          type: 'id'
        },
        title: {
          remote: 'title',
          type: 'string'
        },
        descriptionText: {
          remote: 'description_text',
          type: 'string'
        },
        descriptionMedia: {
          remote: 'description_picture',
          type: 'integer' //XXX: really few chance to have an integer here
        },
        isDraft: {
          remote: 'is_draft',
          type: 'boolean',
          defaultValue: true
        },
        isPublic: {
          remote: 'is_public',
          type: 'boolean',
          defaultValue: false
        },
        paragraphs: {
          remote: 'paragraphs',
          type: 'orderedArray'
        },
        authorUsers: {
          remote: 'author_users',
          type: 'array'
        },
        travelbook: {
          remote: 'travelbook',
          type: 'Model.Travelbook'
        },
        url: {
            remote: 'url',
            type: 'string'
        }
    },
    Travelbook: {
        id: {
          remote: 'id',
          type: 'id'
        },
        title: {
          remote: 'title',
          type: 'string'
        },
        beginDate: {
            remote: 'begin_date',
            type: 'date'
        },
        endDate: {
            remote: 'end_date',
            type: 'date'
        },
        articles: {
            remote: 'articles',
            type: 'orderedArray'
        },
        isDraft: {
          remote: 'is_draft',
          type: 'boolean',
          defaultValue: true
        },
        isPublic: {
          remote: 'is_public',
          type: 'boolean',
          defaultValue: false
        }
    },
    Picture: {
        id: {
          remote: 'id',
          type: 'id'
        },
        createAt: {
          remote: 'created_at',
          type: 'datetime'
        },
        updateAt: {
          remote: 'update_at',
          type: 'datetime'
        },
        smallPicture: {
          remote: 'small_picture_link',
          type: 'Model.Link'
        },
        mediumPicture: {
          remote: 'medium_picture_link',
          type: 'Model.Link'
        },
        bigPicture: {
          remote: 'big_picture_link',
          type: 'Model.Link'
        },
        authorUsers: {
          remote: 'author_users',
          type: 'orderedArray'
        },
        deleteAt: {
          remote: 'deleted_at',
          type: 'datetime'
        }
    },
    Link: {
        id: {
          remote: 'id',
          type: 'id'
        },
        updateDate: {
          remote: 'updated_at',
          type: 'datetime'
        },
        createAt: {
          remote: 'create_at',
          type: 'datetime'
        },
        caption: {
          remote: 'caption',
          type: 'string'
        },
        storage: {
          remote: 'storage',
          type: 'string'
        },
        url: {
          remote: 'url',
          type: 'string'
        },
        authorUsers: {
          remote: 'author_users',
          type: 'orderedArray'
        }
    }
};

ModelsManager.$inject = ['NgBackboneModel', 'NgBackboneCollection', 'ModelsDefinition'];

function ModelsManager(NgBackboneModel, NgBackboneCollection, ModelsDefinition) {

    var models = {};
    
    /* Not used at the moment but it stores what would be the default
    * values if creating an object from scratch.
    */
    function defaultsModel(schema) {
        var byDefault = {};
        _.each(schema, function(fieldDef, fieldName) {
         var defaultValue = null;
         if(fieldDef.defaultValue != null) {
             defaultValue = angular.copy(fieldDef.defaultValue);
         } else {
            if(fieldDef.type) {
                if(fieldDef.type === 'string') {
                    defaultValue = '';
                } else if (fieldDef.type === 'array' || fieldDef.type === 'orderedArray') {
                    defaultValue = [];
                } else if (fieldDef.type === 'boolean') {
                    defaultValue = false;
                }
            }
        }
         byDefault[fieldName] = defaultValue;
        });
        return byDefault;
    }
    
    /* Set 'undefined' for every property of the model
    * because a property set to 'undefined' means 'not initialized'
    * and will not be sent to the server in case of PATCH request.
    * That makes possible to work with partial objects.
    */
    function initModel(schema) {
        var initValues = {};
        
        _.each(schema, function(fieldDef, fieldName) {
           initValues[fieldName] = undefined; 
        });
        
        return initValues;
    }

    function define(name, schema) {
        var url = '/api/0.1/'+name.toLowerCase()+'s';
        var searchUrl = '/api/0.1/search/'+name.toLowerCase()+'s';
        var Model = NgBackboneModel.extend({
          defaults: initModel(schema),
          __defaults: defaultsModel(schema),
          __schema: schema,
          urlRoot: url,
          sync: function(method, model, options) {
              console.log('Sync method of Model has been called');
              options = options || {};
              options.success = function(result) {
                  model.__serverAttrs = _.clone(model.attributes);
              }
              return NgBackboneModel.prototype.sync.apply(this, arguments);
          }
        });
        var ModelCollection = NgBackboneCollection.extend({
          model: Model,
          url: url,
          sync: function(method, collection, options) {
                console.log('Sync method of Collection has been called');
                options = options || {};
                if (collection.methodUrl && collection.methodUrl(collection.toLowerCase())) {
                    options.url = collection.methodUrl(method.toLowerCase());
                }
                if(method === 'read' && options != null && options.data != null) {
                    options.url = searchUrl;
                }
                options.success(function() {
                    collection.$models.forEach(function($model) {
                        console.log("Saving the server attrs");
                       $model.__serverAttrs = $model.attributes; 
                    });
                });
                return NgBackboneCollection.prototype.sync.apply(this, arguments);
            }
        });
        models[name] = Model;
        var collectionName = name+'Collection';
        models[collectionName] = ModelCollection;
    }

    _.each(ModelsDefinition, function(value, key) {
        define(key, value);
    });

    return models;
}

function JetlagUtils() {
	function findValue(collection, value) {
		return _.find(collection, function(item) {
			return item === value;
			});
	}

	return {
		findValue: findValue
	}
}

function momentToString() {
    return function(input) {
        /* XXX: temp hack cause moment-picker has strange behaviour
        * cf https://github.com/indrimuska/angular-moment-picker/issues/60
        */
        return moment.isMoment(input) ? input.format("DD.MM.YYYY") : input;
    }
}

angular
  .module('jetlag.webapp.base', ['ngBackbone'])
  .constant('ModelsDefinition', modelsDefinition)
  .factory('ModelsManager', ModelsManager)
  .factory('JetlagUtils', JetlagUtils)
  .filter('momentToString', momentToString);
  
})(angular);