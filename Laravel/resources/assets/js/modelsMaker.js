(function(angular) {
    'use strict';
    
    function ModelsMaker() {
        
        var apiVersion = "0.1";

        /* The different status one instance of a Model can be,
         * only 'STABLE' means there is no pending request made for
         * this model (at least not via the normal way)
         */
        var STATUS = {
            STABLE: 'STABLE',
            FETCHING: 'FETCHING',
            CREATING: 'CREATING',
            SAVING: 'SAVING',
            DELETING: 'DELETING',
            DELETED: 'DELETED'
        }

        /* Construct a plain Javascript model error object
        * for both server and front sides
        */
        function modelError(error, isServerError) {
            var err = {
                isServerError: isServerError,
                error: {}
            };
            
            if(_.isString(error)) {
                err.error.code = 0;
                err.error.errMessage = error;
            } else {
                err.error = error;
            }
            
            return err;
        }
        
        /* Utility function used by the CRUD methods of the model
        * to retrieve what url to use for the HTTP request.
        * <options> are the same options than passed to the CRUD
        * method.
        */
        function getModelUrl(model, options) {
            var url = null;
            
            if(options.url !== undefined) {
                url = options.url;
            } else {
                url = model.getUrl()
            }
            return url;
        }
        
        function getDefaultModelApiUrl(name) {
            return "/api/" + apiVersion + "/" + name ;
        }
        
        function makeReverseSchema(schema) {
            var reverseSchema = {};
            
            _.each(schema, function(pptyValue, pptyName) {
                reverseSchema[pptyValue.remote] = {
                    local: pptyName,
                    type: pptyValue.type
                }
            });
            
            return reverseSchema;
        }
        
        function Generic(name, schema, initialValues) {
            function Model() {
                this._schema = angular.copy(schema);
                this._reverseSchema = makeReverseSchema(this._schema);
                this._default = angular.copy(initialValues);
                this._name = name;
                this._serverAttributes = {};
                this.url = undefined;
                this._status = STATUS.STABLE;
                this._defaultUrl = getDefaultModelApiUrl(name);
            }
            
            Model.prototype = {
                getUrl: function() {
                    if (this.url !== undefined) {
                        return this.url;
                    } else if(this._defaultUrl !== undefined) {
                        return this._defaultUrl;
                    }
                    
                    console.warn("No default url defined for " + model._name);
                    return null;
                },
                fetch: function(options) {
                    options = options || {};
                    
                    var model = this;
                    var url = null;
                    var q = $q.defer();

                    /* Can't retrieve deleted models */
                    if(model._status === STATUS.DELETED) {
                        var errorMessage = "Unable to fetch resource " + model._name + ": resource is deleted.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    /* Retrieve what URL should be used to get the resource */
                    url = getModelUrl(model, options);
                    if(url === null) {
                        var errorMessage = "Unable to fetch resource " + model._name + ": no URL defined.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(model.isNew()) {
                        var errorMessage = "Unable to fetch resource " + model._name + ": cannot fetch new resources.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    url += "/" + model.id;
                    
                    model._status = STATUS.FETCHING;
                    $http.get(url).then(function(result) {
                        model._map(result);
                        model._saveServerAttributes();
                        model._status = STATUS.STABLE;
                        q.resolve(result);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error, true));
                    });
                    
                    return q.promise;
                },
                save: function(options, isPatch) {
                    var model = this;
                    var q = $q.defer();
                    var validationErrors, serverAttrs, url, requestPromise;

                    /* Can't save deleted models */
                    if(model._status === STATUS.DELETED) {
                        var errorMessage = "Unable to save resource " + model._name + ": resource is deleted.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    if(!model.hasChanged()) {
                        q.resolve(model);
                        return q.promise;
                    }
                    
                    validationErrors = model.validate();
                    if(!_.isEmpty(validationErrors)) {
                        console.warn("Unable to save resource " + model._name + ": validation failed.");
                        _.each(validationErrors, function(value, attrName) {
                           console.warn("  " + attrName + " -> " + value);
                        });
                        q.reject(modelError(validationErrors, false));
                        return q.promise;
                    }
                    
                    serverAttrs = model._unmap();
                    url = getModelUrl(model, options);
                    
                    if(url === null) {
                        var errorMessage = "Unable to save resource " + model._name + ": no URL defined.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(options.isPatch === true || isPatch === true) {
                        if(model.isNew()) {
                            var errorMessage = "Unable to save resource " + model._name + ": cannot make PATCH request on new models.";
                            console.warn(errorMessage);
                            return q.reject(modelError(errorMessage, false));
                        } else {
                            url += "/" + model.id;
                            model._status = STATUS.SAVING;
                            requestPromise = $http.patch(url, serverAttrs);
                        }
                    } else {
                        if(model.isNew()) {
                            model._status = STATUS.CREATING;
                        } else {
                            url += "/" + model.id;
                            model._status = STATUS.SAVING;
                        }
                        requestPromise = $http.post(url, serverAttrs)
                    }
                    
                    requestPromise.then(function(result) {
                        model._map(result);
                        model._saveServerAttributes();
                        model._status = STATUS.STABLE;
                        q.resolve(result);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error, true));
                    });
                    
                    return q.promise;
                },
                _saveServerAttributes: function() {
                    var model = this;
                    
                    _.each(model, function(value, name) {
                        if(model._schema[name] !== undefined && !name.startsWith('_')) {
                           model._serverAttributes[name] = angular.copy(value);
                       } 
                    });
                },
                delete: function(options) {
                    var model = this;
                    var url = null;
                    var q = $q.defer();

                    /* Can't delete deleted models */
                    if(model._status === STATUS.DELETED) {
                        var errorMessage = "Unable to delete resource " + model._name + ": resource is already deleted.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    url = getModelUrl(options);
                    if(url === null) {
                        var errorMessage = "Unable to delete resource " + model._name + ": no URL defined.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(model.isNew()) {
                        var errorMessage = "Unable to delete resource " + model._name + ": cannot delete new models.";
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    url += '/' + model.id;
                    model._status = STATUS.DELETING;

                    $http.delete(url).then(function(result) {
                        model._status = STATUS.DELETED;
                        q.resolve(result);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error, true));
                    });
                    
                    return q.promise;
                },
                resetLocal: function() {
                    var model = this;
                    
                    if(!model.hasChanged()) {
                        return;
                    }
                    
                    _.each(model._serverAttributes, function(value, name) {
                       model[name] = angular.copy(value); 
                    });
                },
                /* XXX: should call the validate function, if defined, of the different attributes */
                validate: function() {
                    console.warn("`Validate` not implemented yet ! (considered valid as long as not implemented)");
                    var validationErrors = {};
                    
                    return validationErrors;
                },
                isValid: function() {
                    var model = this;
                    var validationErrors = model.validate();

                    return _.isEmpty(validationErrors);
                },
                /* XXX: should also call the map function, if defined, of the different attributes */
                _map: function(serverAttrs) {
                    var reverseSchema = this._reverseSchema;
                    var model = this;
                    
                    _.each(serverAttrs, function(serverAttrValue, serverAttrName) {
                        if(reverseSchema[serverAttrName] !== undefined) {
                            model[reverseSchema[serverAttrName].local] = serverAttrValue;
                        }
                    });
                },
                /* XXX: should also call the unmap function, if defined, of the different attributes */
                _unmap: function() {
                    var schema = this._schema;
                    var model = this;
                    var serverAttrs = {};
                    
                    _.each(model, function(localAttrValue, localAttrName) {
                       if(schema[localAttrName] !== undefined) {
                           serverAttrs[schema[localAttrName].remote] = localAttrValue;
                       } 
                    });
                    
                    return serverAttrs;
                },
                isNew: function() {
                    return this.id !== undefined && this.id !== null;
                },
                /* XXX: call the equals function, if defined, instead of angular.equals */
                changedAttributes: function() {
                    var model = this;
                    var changedAttr = [];

                    _.each(model._schema, function(value, name) {
                        if(!angular.equals(model[name], model._serverAttributes[name])) {
                            changedAttr.push(name);
                        }
                    });
                    return changedAttr;
                },
                hasChanged: function() {
                    var changedAttributes = this.changedAttributes();

                    return changedAttributes.lenght > 0;
                },
                clone: function() {
                    var model = this;
                    var newModel = new model.constructor();
                    
                    newModel._serverAttributes = angular.copy(model._serverAttributes);
                    newModel._status = angular.copy(model._status);
                    newModel.url = angular.copy(model.url);
                    newModel._defaultUrl = angular.copy(model._defaultUrl);
                    _.each(newModel._schema, function(value, name) {
                       newModel[name] = angular.copy(model[name]); 
                    });
                    
                    return newModel;
                }
            }
            return Model;
        }
        return Generic;
    }
    
    function ModelsManager(ModelsMaker) {
        var models = {};
        
        models.Article = ModelsMaker("Article");
        models.Travelbook = ModelsMaker("Travelbook");
        
        return models;
    }
    
  ModelsManager.$inject = ['ModelsMaker'];
  
  angular
  .module('jetlag.webapp.etc', [])
  .factory('ModelsMaker', ModelsMaker)
  .factory('JLModelsManager', ModelsManager);
})(angular);