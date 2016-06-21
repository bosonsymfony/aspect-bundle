/**
 * AspectBundle/Resources/public/adminApp/controllers/aspectGestionCtrl.js
 */
angular.module('app')
    .controller('aspectGestionCtrl',
        ['$scope', 'aspectGestionSvc', 'toastr', '$mdDialog',
            function ($scope, aspectGestionSvc, toastr, $mdDialog) {

                aspectGestionSvc.getCSRFtoken()
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
                $scope.numericMess = "Solo se permiten números.";

                $scope.regexacc_cont = '[a-zA-Z]+:[a-z0-9A-Z]+';
                $scope.regexserv = '[a-zA-Z0-9. ]+';
                $scope.regexacc_contMess = "Formato permitido: Controlador:Operación.";
                $scope.regexservMess = "Solo se permiten letras, números y caracter especial '.' .";
                $scope.regexnombaspectoMess = "Solo se permiten letras, números y caracter especial '_'.";

                $scope.modificable = false;
                $scope.wasmodified = false;

                $scope.modif = function () {
                    $scope.wasmodified = true;
                };

                aspectGestionSvc.getBundlesWithAspects()
                    .success(function (response) {
                        $scope.info = response;
                    })
                    .error(function (response) {
                        toastr.error(response);
                    });

                $scope.loadAspect = function () {
                    $scope.aspectosbundle = null;
                    $scope.aspecto = null;
                    aspectGestionSvc.getAspectos($scope.bundle)
                        .success(function (response) {
                            $scope.aspectosbundle = response;
                        })
                        .error(function (response) {
                            alert(response);
                        });
                };

                $scope.eliminar = function (ev) {

                    $mdDialog.show({
                        clickOutsideToClose: true,
                        controller: 'DialogController',
                        focusOnOpen: false,
                        targetEvent: ev,
                        locals: {
                            entities: $scope.selected
                        },
                        templateUrl: $scope.$urlAssets + 'bundles/trazas/adminApp/views/confirm-dialog.html'
                    }).then(function (answer) {
                        //console.log(answer);
                        if (answer == 'Aceptar') {
                            aspectGestionSvc.deleteAspect($scope.bundle, $scope.aspecto)
                                .success(function (response) {
                                    toastr.success(response);
                                    //para limpiar los campos sin tener q recargar el html completo
                                    $scope.aspectosbundle = null;
                                    $scope.bundle = null;
                                    $scope.newAspecto = null;
                                    $scope.controllerAction = null;
                                    $scope.type = null;
                                    $scope.serviceName = null;
                                    $scope.method = null;
                                    $scope.order = null;
                                    $scope.aspecto = null;
                                    $scope.wasmodified = false;
                                    aspectGestionSvc.getBundlesWithAspects()
                                        .success(function (response) {
                                            $scope.info = response;
                                        })
                                        .error(function (response) {
                                            toastr.error(response);
                                        });
                                })
                                .error(function (response) {
                                    toastr.error(response);
                                    console.log(response);
                                });
                        } else {
                            // alert("Cancelar");
                        }
                    });
                };

                $scope.enablemod = function () {
                    if ($scope.bundle != null && $scope.aspecto != null) {
                        $scope.modificable = true;
                        aspectGestionSvc.getDataAspectByBundleAspect($scope.bundle, $scope.aspecto)
                            .success(function (response) {
                                $scope.newAspecto = $scope.aspecto;
                                $scope.controllerAction = response.controller_action;
                                $scope.type = response.type;
                                $scope.serviceName = response.service_name;
                                $scope.method = response.method;
                                $scope.order = response.order;
                            })
                            .error(function (response) {
                                toastr.error(response);
                                console.log(response);
                            });
                    }

                };

                $scope.guardarCambios = function (ev) {

                    $mdDialog.show({
                        clickOutsideToClose: true,
                        controller: 'DialogController',
                        focusOnOpen: false,
                        targetEvent: ev,
                        locals: {
                            entities: $scope.selected
                        },
                        templateUrl: $scope.$urlAssets + 'bundles/trazas/adminApp/views/confirm-dialog.html'
                    }).then(function (answer) {
                        //console.log(answer);
                        if (answer == 'Aceptar') {
                            var data = {
                                uci_boson_aspectbundle_data: {
                                    bundle: $scope.bundle,
                                    nombreAspecto: $scope.newAspecto,
                                    nombreAspectoAnterior: $scope.aspecto,
                                    controllerAction: $scope.controllerAction,
                                    type: $scope.type,
                                    serviceName: $scope.serviceName,
                                    method: $scope.method,
                                    order: $scope.order,
                                    _token: $scope.token
                                }
                            };

                            aspectGestionSvc.ModifyData(data)
                                .success(function (response) {
                                    console.log(response);
                                    toastr.success(response);
                                    aspectGestionSvc.getBundlesWithAspects()
                                        .success(function (response) {
                                            $scope.info = response;
                                            $scope.aspectosbundle = null;
                                            $scope.bundle = null;
                                            $scope.newAspecto = null;
                                            $scope.controllerAction = null;
                                            $scope.type = null;
                                            $scope.serviceName = null;
                                            $scope.method = null;
                                            $scope.order = null;
                                            $scope.wasmodified = false;
                                        })
                                        .error(function (response) {
                                            toastr.error(response);
                                        });
                                })
                                .error(function (response) {
                                    toastr.error(response);
                                });
                        } else {
                            // alert("Cancelar");
                        }
                    });
                };
            }
        ]
    )
    .controller('DialogController',
        ['$scope', 'aspectGestionSvc', 'toastr', '$mdDialog',
            function ($scope, aspectGestionSvc, toastr, $mdDialog) {
                $scope.hide = function () {
                    $mdDialog.hide();
                };
                $scope.cancel = function () {
                    $mdDialog.cancel();
                };
                $scope.answer = function (answer) {
                    $mdDialog.hide(answer);
                };

            }
        ]
    );