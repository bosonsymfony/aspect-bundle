
/**
 * AspectBundle/Resources/public/adminApp/filters/aspectHomeFilter.js
 */
angular.module('app')
        .filter('aspectHomeFilter',
                function () {
                    return function (input) {
                        input = input || '';
                        var out = "";
                        for (var i = 0; i < input.length; i++) {
                            out = input.charAt(i) + out;
                        }
                        return out;
                    }
                }
        );