(function(angular) {
    'use strict';

    var commonProperties = {
        id: {
            remote: 'id',
            type: 'id'
        },
        createdAt: {
            remote: 'created_at',
            type: 'datetime'
        },
        updatedAt: {
            remote: 'updated_at',
            type: 'datetime'
        },
        deletedAt: {
            remote: 'deleted_at',
            type: 'datetime'
        }
    };
    
    function ModelsMaker($q, $http, typeMapper) {
        
        var apiVersion = '0.1';

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
        };

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
            return '/api/' + apiVersion + '/' + pluralize(name.toLowerCase()) ;
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
                _.assign(this._schema, commonProperties);
                this._reverseSchema = makeReverseSchema(this._schema);
                this._default = angular.copy(initialValues);
                this._name = name;
                this._serverAttributes = {};
                this._url = undefined;
                this._status = STATUS.STABLE;
                this._defaultUrl = getDefaultModelApiUrl(name);

                var model = this;
                Object.keys(schema).forEach(function(pptyName) {
                    model[pptyName] = initialValues != null ? initialValues[pptyName] : undefined;
                });
            }
            
            Model.prototype = {
                getUrl: function() {
                    if (this._url !== undefined) {
                        return this._url;
                    } else if(this._defaultUrl !== undefined) {
                        return this._defaultUrl;
                    }
                    
                    console.warn('No default nor custom url defined for ' + model._name);
                    return null;
                },
                fetch: function(options) {
                    options = options || {};
                    
                    var model = this;
                    var url = null;
                    var q = $q.defer();

                    /* Can't retrieve deleted models */
                    if(model._status === STATUS.DELETED) {
                        var errorMessage = 'Unable to fetch resource ' + model._name + ': resource is deleted.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    /* Retrieve what URL should be used to get the resource */
                    url = getModelUrl(model, options);
                    if(url === null) {
                        var errorMessage = 'Unable to fetch resource ' + model._name + ': no URL defined.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(model.isNew()) {
                        var errorMessage = 'Unable to fetch resource ' + model._name + ': cannot fetch new resources.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    url += '/' + model.id;
                    
                    model._status = STATUS.FETCHING;
                    $http.get(url).then(function(result) {
                        model._map(result.data);
                        model._saveServerAttributes();
                        model._status = STATUS.STABLE;
                        q.resolve(result.data);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error.data, true));
                    });
                    
                    return q.promise;
                },
                save: function(options, isPatch) {
                    var model = this;
                    var q = $q.defer();
                    var validationErrors, serverAttrs, url, requestPromise;

                    /* Can't save deleted models */
                    if(model._status === STATUS.DELETED) {
                        var errorMessage = 'Unable to save resource ' + model._name + ': resource is deleted.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    if(!model.hasChanged()) {
                        q.resolve(model);
                        return q.promise;
                    }
                    
                    validationErrors = model.validate();
                    if(!_.isEmpty(validationErrors)) {
                        console.warn('Unable to save resource ' + model._name + ': validation failed.');
                        _.each(validationErrors, function(value, attrName) {
                           console.warn('  ' + attrName + ' -> ' + value);
                        });
                        q.reject(modelError(validationErrors, false));
                        return q.promise;
                    }
                    
                    serverAttrs = model._unmap();
                    url = getModelUrl(model, options);
                    
                    if(url === null) {
                        var errorMessage = 'Unable to save resource ' + model._name + ': no URL defined.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(options.isPatch === true || isPatch === true) {
                        /* XXX: we should only send changed attrs here */
                        if(model.isNew()) {
                            var errorMessage = 'Unable to save resource ' + model._name + ': cannot make PATCH request on new models.';
                            console.warn(errorMessage);
                            return q.reject(modelError(errorMessage, false));
                        } else {
                            url += '/' + model.id;
                            model._status = STATUS.SAVING;
                            requestPromise = $http.patch(url, serverAttrs);
                        }
                    } else {
                        var httpMethod;

                        if(model.isNew()) {
                            httpMethod = $http.post;
                            model._status = STATUS.CREATING;
                        } else {
                            httpMethod = $http.put;
                            url += '/' + model.id;
                            model._status = STATUS.SAVING;
                        }
                        requestPromise = httpMethod(url, serverAttrs);
                    }
                    
                    requestPromise.then(function(result) {
                        model._map(result.data);
                        model._saveServerAttributes();
                        model._status = STATUS.STABLE;
                        q.resolve(result.data);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error.data, true));
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
                        var errorMessage = 'Unable to delete resource ' + model._name + ': resource is already deleted.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    url = getModelUrl(options);
                    if(url === null) {
                        var errorMessage = 'Unable to delete resource ' + model._name + ': no URL defined.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }
                    
                    if(model.isNew()) {
                        var errorMessage = 'Unable to delete resource ' + model._name + ': cannot delete new models.';
                        console.warn(errorMessage);
                        return q.reject(modelError(errorMessage, false));
                    }

                    url += '/' + model.id;
                    model._status = STATUS.DELETING;

                    $http.delete(url).then(function(result) {
                        model._status = STATUS.DELETED;
                        q.resolve(result.data);
                    }, function(error) {
                        model._status = STATUS.STABLE;
                        q.reject(modelError(error.data, true));
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
                    console.warn('`Validate` not implemented yet ! (considered valid as long as not implemented)');
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
                    
                    _.each(this._schema, function(frontAttrValue, frontAttrName) {
                        if(frontAttrValue.remote !== undefined) {
                            if(typeof frontAttrValue.map === 'function') {
                                model[frontAttrName] = frontAttrValue.map(serverAttrs[frontAttrValue.remote]);
                            } else {
                                model[frontAttrName] =
                                    typeMapper.map(serverAttrs[frontAttrValue.remote], frontAttrValue.type);
                            }
                        } else {
                            model[frontAttrName] = undefined;
                        }
                    });
                },
                /* XXX: should also call the unmap function, if defined, of the different attributes */
                _unmap: function() {
                    var schema = this._schema;
                    var model = this;
                    var serverAttrs = {};

                    _.each(this._reverseSchema, function(serverAttrValue, serverAttrName) {
                        if(serverAttrValue.local !== undefined) {
                            if(typeof serverAttrValue.unmap === 'function') {
                                serverAttrs[serverAttrName] = serverAttrValue.unmap(model[serverAttrValue.local]);
                            } else {
                                serverAttrs[serverAttrName] =
                                typeMapper.unmap(model[serverAttrValue.local], serverAttrValue.type);
                            }
                        } else {
                            serverAttrs[serverAttrName] = null;
                        }
                    });
                    
                    return serverAttrs;
                },
                isNew: function() {
                    return this.id === undefined || this.id === null;
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

                    return changedAttributes.length > 0;
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

            Model.fetchCollection = function(options) {
                var url = getDefaultModelApiUrl(name);
                var q = $q.defer();

                options = options || {};

                if(options.url !== undefined) {
                    url = options.url;
                }

                $http.get(url, {
                    params: options.params
                }).then(function(results) {
                    var collection = [];

                    results.data.forEach(function(result) {
                        var instance = new Model();

                        instance._status = STATUS.CREATING;
                        instance._map(result);
                        instance._saveServerAttributes();
                        instance._status = STATUS.STABLE;
                        collection.push(instance);
                    });

                    q.resolve(collection);
                }, function(error) {
                    q.reject(modelError(error.data, true));
                });

                return q.promise;
            }

            return Model;
        }
        return Generic;
    }
    
    function ModelsManager(ModelsMaker, paragraphsService) {
        var models = {};

        var modelsDefinition = {
            Article: {
                title: {
                  remote: 'title',
                  type: 'string'
                },
                date: {
                    remote: 'date',
                    type: 'datetime'
                },
                descriptionText: {
                  remote: 'description_text',
                  type: 'string'
                },
                descriptionMedia: {
                  remote: 'description_media',
                  type: 'Model.Picture'
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
                  type: 'orderedArray',
                  map: paragraphsService.map,
                  unmap: paragraphsService.unmap
                },
                authors: {
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
                },
                mainPlace: {
                    remote: 'main_place',
                    type: 'Model.Place'
                }
            },
            Travelbook: {
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
                }
            },
            Link: {
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
        
        models.Article = ModelsMaker('Article', modelsDefinition.Article);
        models.Travelbook = ModelsMaker('Travelbook', modelsDefinition.Travelbook);
        
        return models;
    }
    
  ModelsManager.$inject = ['ModelsMaker', 'paragraphsService'];
  ModelsMaker.$inject = ['$q', '$http', 'typeMapper'];
  
  angular
  .module('jetlag.webapp.etc', ['jetlag.webapp.components.paragraphs'])
  .factory('ModelsMaker', ModelsMaker)
  .factory('JLModelsManager', ModelsManager);
})(angular);