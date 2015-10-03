angular
  .module('jetlag.webapp.base', ['ngBackbone'])
  .factory('ModelsManager', ModelsManager)
  
   
  ModelsManager.$inject = ['NgBackboneModel', 'NgBackboneCollection'];
  
  
function ModelsManager(NgBackboneModel, NgBackboneCollection) {
  
  function define(name, model) {
    var url = '/api/'+name.toLowerCase();
    model.urlRoot = url;	
    var Model = NgBackboneModel.extend(model);
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
  define('Article', {});
  define('User', {});
  define('TravelBook', {});
  
  return returned;
}
