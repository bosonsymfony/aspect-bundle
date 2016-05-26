
/**
 * AspectBundle/Resources/public/adminApp/services/aspectAddSvc.js
 */
angular.module('app')
    .service('aspectAddSvc', ['$http',
            function ($http) {
                var message = '';

                function setMessage(newMessage) {
                    message = newMessage;
                }

                function getMessage() {
                    return message;
                }

                function showCurrentInfo() {
                    return $http.get(Routing.generate('aspects_get_info',{},true));
                }

                function writeYAML(data) {
                    return $http.post(Routing.generate('aspect_save_data',{},true),data);
                }

                return {
                    setMessage: setMessage,
                    getMessage: getMessage,
                    showCurrentInfo: showCurrentInfo,
                    writeYAML: writeYAML,
                    $get: function () {

                    }
                }
            }
        ]
    );