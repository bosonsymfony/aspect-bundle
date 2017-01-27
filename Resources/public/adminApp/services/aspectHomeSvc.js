
/**
 * AspectBundle/Resources/public/adminApp/services/aspectHomeSvc.js
 */
angular.module('app')
    .service('aspectHomeSvc', ['$http',
            function ($http) {
                var message = '';

                function setMessage(newMessage) {
                    //console.log(newMessage)
                    message = newMessage;
                }

                function getMessage() {
                    return message;
                }

                function showCurrentInfo() {
                    return $http.get(Routing.generate('prueba_get_info',{},true));
                }

                return {
                    setMessage: setMessage,
                    getMessage: getMessage,
                    showCurrentInfo: showCurrentInfo,
                    $get: function () {

                    }
                }
            }
        ]
    );