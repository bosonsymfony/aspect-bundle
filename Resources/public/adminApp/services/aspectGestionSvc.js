
/**
 * AspectBundle/Resources/public/adminApp/services/aspectGestionSvc.js
 */
angular.module('app')
    .service('aspectGestionSvc', ['$http',
            function ($http) {
                var message = '';

                function setMessage(newMessage) {
                    message = newMessage;
                }

                function getMessage() {
                    return message;
                }

                function getBundlesWithAspects() {
                    return $http.get(Routing.generate('aspects_bundles_withaspects',{},true));
                }

                function getAspectos(bundle) {
                    return $http.get(Routing.generate('aspects_get_nombAspect',{bundle: bundle},true));
                }

                function getDataAspectByBundleAspect(bundle,nombreAspecto) {
                    return $http.get(Routing.generate('aspects_get_dataAspect',{bundle: bundle, nombreAspecto: nombreAspecto}));
                }

                function deleteAspect(bundle,nombreAspecto) {
                    return $http.get(Routing.generate('aspect_erase_data',{bundle: bundle, nombreAspecto: nombreAspecto}));
                }

                function ModifyData(data) {
                    return $http.post(Routing.generate('aspect_modify_data',{},true),data);
                }

                return {
                    setMessage: setMessage,
                    getMessage: getMessage,
                    getBundlesWithAspects: getBundlesWithAspects,
                    getAspectos:getAspectos,
                    getDataAspectByBundleAspect: getDataAspectByBundleAspect,
                    deleteAspect:deleteAspect,
                    ModifyData: ModifyData,
                    $get: function () {

                    }
                }
            }
        ]
    );