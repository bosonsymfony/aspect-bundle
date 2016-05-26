
/**
 * AspectBundle/Resources/public/adminApp/controllers/aspectHomeCtrl.js
 */
angular.module('app')
        .controller('aspectHomeCtrl',
                ['$scope', 'aspectHomeSvc',
                    function ($scope, aspectHomeSvc) {

                        $scope.showSource = false;
                        aspectHomeSvc.setMessage('Welcome to the AspectBundle example configuration.');
                        $scope.message = aspectHomeSvc.getMessage();

                    }
                ]
        );