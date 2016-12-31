    var app = angular.module("ctrltiendacar", []);

    app.controller("ctrltienda", function($scope, $http) {

        $scope.posts = [];

        $http.get(articulo_id).success(function(data, timeout) {
            $scope.posts = data;
            console.log(data);
        }).error(function(err) {
        })


    });
