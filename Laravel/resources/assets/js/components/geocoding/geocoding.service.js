(function(angular) {
'use strict';

angular
  .module('jetlag.webapp.components.geocoding')
  .service('geocodingService', geocodingService);

geocodingService.$inject = ['$http', '$q'];

function geocodingService($http, $q) {
    var API_KEY = 'search-KjBcCm0';
    var BASE_URL = 'https://search.mapzen.com/v1/';

    this.geocode = function(input) {
        var params = {
            text: input,
            api_key: API_KEY
        };
        var q = $q.defer();

        $http.get(BASE_URL + 'search', {
            params: params
        }).success(function(results) {
            q.resolve(results);
        }).error(function(error) {
            console.warn('Unable to retrieve geocoding results', error);
            q.reject(error);
        });

        return q.promise;
    }

    this.reverseGeocode = function(latLng) {
        var params = {
            api_key: API_KEY
        };
        var q = $q.defer();

        if(!_.isObject(latLng) || latLng.lat == null || latLng.lng == null) {
            console.warn('The `latLng` object is not correct');
            q.reject();
        } else {
            params['point.lat'] = latLng.lat;
            params['point.lon'] = latLng.lng;
        }

        $http.get(BASE_URL + 'reverse', {
            params: params
        }).success(function(results) {
            q.resolve(results);
        }, function(error) {
            console.warn('Unable to retrieve reverse geocoding results', error);
            q.reject(error);
        });

        return q.promise;
    }
}

})(window.angular);

