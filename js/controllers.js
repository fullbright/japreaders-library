'use strict';

/* Controllers */

var phonecatControllers = angular.module('phonecatControllers', []);

phonecatControllers.controller('PhoneListCtrl', ['$scope', 'Phone', '$http',
  function($scope, Phone, $timeout, $q, $http) {
    $scope.phones = Phone.query();
    $scope.orderProp = 'age';
    $scope.selectedItem  = null;
    $scope.searchText    = null;
    $scope.querySearch   = querySearch;
    $scope.simulateQuery = false;
    $scope.isDisabled    = false;
	$scope.noCache    	 = false;
	
	// Simple GET request example :
	$http.get('/jap-readers-library/bookmark').
	  success(function(data, status, headers, config) {
		// this callback will be called asynchronously
		// when the response is available
		console.log(JSON.stringify(data));
	}).
	error(function(data, status, headers, config) {
		// called asynchronously if an error occurs
		// or server returns response with an error status.
		console.log("Error getting the data.");
	});
	
	/**
     * Search for states... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      var results = query ? $scope.phones.filter( createFilterFor(query) ) : [],
          deferred;
      if (self.simulateQuery) {
        deferred = $q.defer();
        $timeout(function () { deferred.resolve( results ); }, Math.random() * 1000, false);
        return deferred.promise;
      } else {
        return results;
      }
    }
	
	/**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);
      return function filterFn(state) {
        return (angular.lowercase(state.name).indexOf(lowercaseQuery) === 0);
      };
    }
  }]);

phonecatControllers.controller('PhoneDetailCtrl', ['$scope', '$routeParams', 'Phone',
  function($scope, $routeParams, Phone) {
    $scope.phone = Phone.get({phoneId: $routeParams.phoneId}, function(phone) {
      $scope.mainImageUrl = phone.images[0];
    });

    $scope.setImage = function(imageUrl) {
      $scope.mainImageUrl = imageUrl;
    }
  }]);
