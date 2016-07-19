angular
  .module('jetlag.webapp.base', ['ngBackbone'])
  .factory('ModelsManager', ModelsManager)
  .factory('JetlagUtils', JetlagUtils);

ModelsManager.$inject = ['NgBackboneModel', 'NgBackboneCollection'];

function ModelsManager(NgBackboneModel, NgBackboneCollection) {

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

  function define(name, schema) {
    var url = '/api/0.1/'+name.toLowerCase()+'s';
    var Model = NgBackboneModel.extend({
      defaults: defaultsModel(schema),
	  $schema: schema,
	  urlRoot: url
    });
    var ModelCollection = NgBackboneCollection.extend({
      model: Model,
	  url: url,
      sync: function(method, model, options) {
            if (model.methodUrl && model.methodUrl(method.toLowerCase())) {
                options = options || {};
                options.url = model.methodUrl(method.toLowerCase());
            }
            if(method === 'read' && options != null && options.data != null) {
                options.url = url+'/search';
            }
           Backbone.sync(method, model, options);
        }
    });
	returned[name] = Model;
	var collectionName = name+'Collection';
	returned[collectionName] = ModelCollection;
  }

  var returned = {
    define: define
  };

  /* Define model */
  define('Article', {
	id: {
	  remote: 'id',
	  type: 'id'
	},
	title: {
  	  remote: 'title',
	  type: 'string'
	},
	descriptionText: {
	  remote: 'descriptionText',
	  type: 'string'
	},
	descriptionPicture: {
	  remote: 'descriptionPicture',
	  type: 'integer'
	},
	is_draft: {
	  remote: 'isDraft',
	  type: 'boolean',
	  defaultValue: true
	},
	is_public: {
	  remote: 'is_public',
	  type: 'boolean'
	},
	paragraphs: {
	  remote: 'paragraphs',
	  type: 'orderedArray'
	},
	authorUsers: {
	  remote: 'authorUsers',
	  type: 'array'
	},
	travelbook: {
	  remote: 'travelbook',
	  type: 'Travelbook'
	},
    url: {
        remote: 'url',
        type: 'string'
    }
  });

  define('User', {});

  define('Travelbook', {
	id: {
	  remote: 'id',
	  type: 'id'
	},
	title: {
  	  remote: 'title',
	  type: 'string'
	},
	begin_date: {
		remote: 'begin_date',
		type: 'date'
	},
	end_date: {
		remote: 'end_date',
		type: 'date'
	},
	articles: {
		remote: 'articles',
		type: 'orderedArray'
	},
	is_draft: {
	  remote: 'is_draft',
	  type: 'boolean',
	  defaultValue: true
	},
	is_public: {
	  remote: 'is_public',
	  type: 'boolean'
	}
  });

  return returned;
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
