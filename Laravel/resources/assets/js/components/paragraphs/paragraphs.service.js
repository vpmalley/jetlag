(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.paragraphs')
  .service('paragraphsService', paragraphsService);

paragraphsService.$inject = ['JetlagUtils', 'typeMapper'];

function paragraphsService(JetlagUtils, typeMapper) {
    var _this = this;
    var types = ['TEXT', 'PICTURE', 'MAP', 'LINK'];
    var schema = {
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
        },
        title: {
            remote: 'title',
            type: 'string'
        },
        blockContent: {
            remote: 'block_content',
            type: 'object'
        },
        blockContentType: {
            remote: 'block_content_type',
            type: 'string'
        }
    };
    var reverseSchema = (function(schema) {
        var reverseSchema = {};
        _.each(schema, function(pptyValue, pptyName) {
            reverseSchema[pptyValue.remote] = {
              local: pptyName,
              type: pptyValue.type
            };
        });
        return reverseSchema;
    })(schema);

    this.contentTypes = JetlagUtils.makeEnum(types);

    this.isEmpty = function(paragraph) {
        return paragraph.blockContent == null;
    }
    
    this.mapText = function(serverText) {
        if(serverText == null) {
            return null;
        } else {
            return {
                id: serverText.id,
                createdAt: serverText.created_at,
                updatedAt: serverText.updated_at,
                deletedAt: serverText.deleted_at,
                value: serverText.value
            };
        }
    }
    
    this.mapPlace = function(serverPlace) {
        if(serverPlace == null) {
            return null;
        } else {
            return {
                id: serverPlace.id,
                label: serverPlace.label,
                latitude: serverPlace.latitude,
                longitude: serverPlace.longitude
            };
        }
    }
    
    this.mapPicture = function(serverPicture) {
        if(serverPicture == null) {
            return null;
        } else {
             return {
                id: serverPicture.id,
                createdAt: serverPicture.created_at,
                updatedAt: serverPicture.updated_at,
                deletedAt: serverPicture.deleted_at,
                smallPicture: _this.mapLink(serverPicture.small_picture),
                mediumPicture: _this.mapLink(serverPicture.medium_picture),
                bigPicture: _this.mapLink(serverPicture.big_picture),
                authors: serverPicture.authors,
                takenAt: typeMapper.map(serverPicture.taken_at, 'datetime'),
                mainPlace: _this.mapPlace(serverPicture.main_place)
             };
        }
    }
    
    this.mapMap = function(serverMap) {
        if(serverMap == null) {
            return null;
        } else {
            return {
                id: serverMap.id,
                createdAt: serverMap.created_at,
                updatedAt: serverMap.updated_at,
                deletedAt: serverMap.deleted_at,
                caption: serverMap.caption,
                center: _this.mapPlace(serverMap.center),
                zoom: serverMap.zoom,
                place: _this.mapPlace(serverMap.place)
            };
        }
    }
    
    this.mapLink = function(serverLink) {
        if(serverLink == null) {
            return null;
        } else {
            return {
                id: serverLink.id,
                url: serverLink.url,
                mimeType: serverLink.mime_type
            };
        }
    }

    this.mapOne = function(serverModel) {
        var frontModel = {};

        /* First, create the front model using the schema and the typeMapper
        * for each property
        */
        _.each(schema, function(pptyValue, pptyName) {
            if(pptyValue.remote !== undefined) {
                frontModel[pptyName] = typeMapper.map(serverModel[pptyValue.remote], pptyValue.type);
            } else {
                frontModel[pptyName] = undefined;
            }
        });

        /* Then apply specific rules */
        if(frontModel.blockContentType === 'text') {
            frontModel.blockContentType = _this.contentTypes.TEXT;
            frontModel.blockContent = _this.mapText(frontModel.blockContent);
        } else if (frontModel.blockContentType === 'picture') {
            frontModel.blockContentType = _this.contentTypes.PICTURE;
            frontModel.blockContent = _this.mapPicture(frontModel.blockContent);
        } else if (frontModel.blockContentType === 'map') {
            frontModel.blockContentType = _this.contentTypes.MAP;
            frontModel.blockContent = _this.mapMap(frontModel.blockContent);
        } else if (frontModel.blockContentType === 'link') {
            frontModel.blockContentType = _this.contentTypes.LINK;
            frontModel.blockContent = _this.mapLink(frontModel.blockContent);
        }

        return frontModel;
    }

    this.map = function(serverModel, frontModel) {
        frontModel = frontModel || [];

        serverModel.forEach(function(paragraph) {
            frontModel.push(_this.mapOne(paragraph));
        });

        return frontModel;
    }

    this.unmapText = function(frontText) {
        if(frontText == null) {
            return null;
        } else {
            return {
                id: frontText.id,
                created_at: frontText.createdAt,
                updated_at: frontText.updatedAt,
                deleted_at: frontText.deletedAt,
                value: frontText.value
            };
        }
    }

    this.unmapPlace = function(frontPlace) {
        if(frontPlace == null) {
            return null;
        } else {
            return {
                id: frontPlace.id,
                label: frontPlace.label,
                latitude: frontPlace.latitude,
                longitude: frontPlace.longitude
            };
        }
    }

    this.unmapPicture = function(frontPicture) {
        if(frontPicture == null) {
            return null;
        } else {
             return {
                id: frontPicture.id,
                created_at: frontPicture.createdAt,
                updated_at: frontPicture.updatedAt,
                deleted_at: frontPicture.deletedAt,
                small_picture: _this.unmapLink(frontPicture.smallPicture),
                medium_picture: _this.unmapLink(frontPicture.mediumPicture),
                big_picture: _this.unmapLink(frontPicture.bigPicture),
                authors: frontPicture.authors,
                taken_at: typeMapper.unmap(frontPicture.takenAt, 'datetime'),
                main_place: _this.unmapPlace(frontPicture.mainPlace)
             };
        }
    }

    this.unmapMap = function(frontMap) {
        if(frontMap == null) {
            return null;
        } else {
            return {
                id: frontMap.id,
                created_at: frontMap.createdAt,
                updated_at: frontMap.updatedAt,
                deleted_at: frontMap.deletedAt,
                caption: frontMap.caption,
                center: _this.unmapPlace(frontMap.center),
                zoom: frontMap.zoom,
                place: _this.unmapPlace(frontMap.place)
            };
        }
    }

    this.unmapLink = function(frontLink) {
        if(frontLink == null) {
            return null;
        } else {
            return {
                id: frontLink.id,
                url: frontLink.url,
                mime_type: frontLink.mimeType
            };
        }
    }

    this.unmapOne = function(frontModel) {
        var serverModel = {};

        /* First, create the server model using the reverseSchema and the typeMapper
        * for each property
        */
         _.each(reverseSchema, function(pptyValue, pptyName) {
            if(pptyValue.local !== undefined) {
                serverModel[pptyName] = typeMapper.map(frontModel[pptyValue.local], pptyValue.type);
            } else {
                serverModel[pptyName] = undefined;
            }
        });

        /* Then apply specific rules */
        if(serverModel.blockContentType === _this.contentTypes.TEXT) {
            serverModel.blockContentType = 'text';
            serverModel.blockContent = _this.unmapText(serverModel.blockContent);
        } else if (serverModel.blockContentType === _this.contentTypes.PICTURE) {
            serverModel.blockContentType = 'picture';
            serverModel.blockContent = _this.unmapPicture(serverModel.blockContent);
        } else if (serverModel.blockContentType === _this.contentTypes.MAP) {
            serverModel.blockContentType = 'map';
            serverModel.blockContent = _this.unmapMap(serverModel.blockContent);
        } else if (serverModel.blockContentType === _this.contentTypes.LINK) {
            serverModel.blockContentType = 'link';
            serverModel.blockContent = _this.unmapLink(serverModel.blockContent);
        }

        return serverModel;
    }

    this.unmap = function(frontModel) {
        var serverModel = [];

        frontModel.forEach(function(paragraph) {
            serverModel.push(_this.unmapOne(paragraph));
        });

        return serverModel;
    }

    this.getFakeParagraphs = function() {
        var paragraphs = [];

        var fakeText, fakePicture, fakeMap, fakeLink;

        fakeText = {
            id: 1,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            value: "Ma jolie histoire"
        };

        fakePicture = {
            id: 1,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            smallPicture: undefined,
            mediumPicture: undefined,
            bigPicture: {
                id: 1,
                url: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Armoiries_r%C3%' +
                    'A9publique_fran%C3%A7aise.svg/2000px-Armoiries_r%C3%A9publique_fran%C3%A7aise.svg.png?uselang=fr',
                mimeType: 'image/png'
            },
            authors: [],
            takenAt: undefined,
            mainPlace: undefined
        };

        fakeMap = {
            id: 1,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            caption: undefined,
            zoom: 15,
            center: {
                id: 1,
                latitude:45.7730879,
                longitude: 4.8418078,
                label: undefined
            },
            place: undefined
        };

        fakeLink = {
            id: 1,
            url: 'https://github.com/vpmalley/jetlag/issues/53#issuecomment-279249254',
            mimeType: 'text/html'
        };

        paragraphs.push({
            id: 1,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            title: undefined,
            blockContent: fakeText,
            blockContentType: _this.contentTypes.TEXT
        });

        paragraphs.push({
            id: 2,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            title: undefined,
            blockContent: fakePicture,
            blockContentType: _this.contentTypes.PICTURE
        });

        paragraphs.push({
            id: 3,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            title: undefined,
            blockContent: fakeMap,
            blockContentType: _this.contentTypes.MAP
        });

        paragraphs.push({
            id: 4,
            createdAt: moment(),
            updatedAt: undefined,
            deletedAt: undefined,
            title: undefined,
            blockContent: fakeLink,
            blockContentType: _this.contentTypes.LINK
        });

        return paragraphs;
    }
}
})(angular);