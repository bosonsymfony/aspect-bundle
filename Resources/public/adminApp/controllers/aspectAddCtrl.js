/**
 * AspectBundle/Resources/public/adminApp/controllers/aspectAddCtrl.js
 */
angular.module('app')
    .controller('aspectAddCtrl',
        ['$scope', 'aspectAddSvc', 'toastr', '$mdDialog',
            function ($scope, aspectAddSvc, toastr, $mdDialog) {

                aspectAddSvc.getCSRFtoken()
                    .success(function (response) {
                        $scope.token = response;
                    })
                    .error(function (response) {
                    });

                $scope.nombmet = '[a-zA-Z0-9_]+';
                $scope.sololetras = '[a-z A-Z]+';
                $scope.numeric = '[0-9]+';
                $scope.regexnombaspecto = '[a-zA-Z0-9_ ]+';
                $scope.nombmetMess = "Solo se permiten letras, números y caracter especial '_'.";
                $scope.sololetrasMess = "Solo se permiten letras.";
                $scope.numericMess= "Solo se permiten números.";

                $scope.regexacc_cont = '[a-zA-Z]+:[a-z0-9A-Z]+';
                $scope.regexserv = '[a-zA-Z0-9. ]+';
                $scope.regexacc_contMess= "Formato permitido: Controlador:Operación.";
                $scope.regexservMess= "Solo se permiten letras, números y caracter especial '.' .";
                $scope.regexnombaspectoMess= "Solo se permiten letras, números y caracter especial '_'.";

                aspectAddSvc.showCurrentInfo()
                    .success(function (response) {
                        $scope.info = response;
                    })
                    .error(function (response) {
                        toastr.error(response);
                    });

                $scope.guardarClick = function (ev) {

                    var confirm = $mdDialog.confirm()
                        .title('Confirmación de cambios')
                        .textContent('¿Está seguro que desea adicionar el aspecto?')
                        .targetEvent(ev)
                        .ok('Si')
                        .cancel('No');
                    $mdDialog.show(confirm).then(function() {
                        //si se selecciona que si:
                        var data = {
                            uci_boson_aspectbundle_data: {
                                bundle: $scope.bundle,
                                nombreAspecto: $scope.nombreAspecto,
                                nombreAspectoAnterior: null,
                                controllerAction: $scope.controllerAction,
                                type: $scope.type,
                                serviceName: $scope.serviceName,
                                method: $scope.method,
                                order: $scope.order,
                                _token: $scope.token
                            }
                        };

                        aspectAddSvc.writeYAML(data)
                            .success(function (response) {
                                console.log(response);
                                toastr.success(response)
                                //location.reload();
                                $scope.bundle = null;
                                $scope.nombreAspecto = null;
                                $scope.controllerAction = null;
                                $scope.type = null;
                                $scope.serviceName = null;
                                $scope.method = null;
                                $scope.order = null;

                            })
                            .error(function (response) {
                                toastr.error(response);
                            });
                    }, function() {
                        //en caso contrario:
                        //toastr.info("Se ha cancelado la operación");
                    });
                };

            }

        ]
    );