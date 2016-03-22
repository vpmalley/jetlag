angular
  .module('jetlag.webapp.base', ['ngBackbone'])
  .factory('ModelsManager', ModelsManager)
  .factory('JetlagUtils', JetlagUtils);
   
ModelsManager.$inject = ['NgBackboneModel', 'NgBackboneCollection'];
  
function ModelsManager(NgBackboneModel, NgBackboneCollection) {
 
 function initializeModel(schema) {
   var byDefault = {};
   _.each(schema, function(fieldDef, fieldName) {
     var defaultValue = null;
	 if(fieldDef.type) {
	   if(fieldDef.type === 'string') {
	     defaultValue = '';
	   } else if (fieldDef.type === 'array' || fieldDef.type === 'orderedArray') {
	     defaultValue = [];
	   }
	 }
	 byDefault[fieldName] = defaultValue;
   });
   return function() {
     this.set(byDefault);
   }
 }
 
  function define(name, schema) {
    var url = '/jetlag/Laravel/public/api/'+name.toLowerCase();
    var Model = NgBackboneModel.extend({
      initialize: initializeModel(schema),
	  $schema: schema,
	  urlRoot: url
    });
    var ModelCollection = NgBackboneCollection.extend({
      model: Model,
	  url: url
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
	  remote: 'is_draft',
	  type: 'boolean'
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
	}
  });
  
  define('User', {});
  
  define('TravelBook', {});
  
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
