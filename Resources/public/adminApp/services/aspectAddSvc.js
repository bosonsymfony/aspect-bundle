
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

                function getCSRFtoken() {
                    return $http.post(Routing.generate('aspects_csrf_form', {}, true), {id_form: 'uci_boson_aspectbundle_data'});
                }

                return {
                    getCSRFtoken: getCSRFtoken,
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