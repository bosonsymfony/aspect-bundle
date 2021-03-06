Componente: AspectBundle
========================

1. Descripción general
----------------------

    El componente AspectBundle permite a las aplicaciones que se están desarrollando gestionar acciones y métodos (aspectos) que se ejecutarán antes o después de la ejecución de un controlador permitiendo al usuario configurar:

        - Controlador y acción en contexto.
        - Si el método o procedimiento se debe ejecutar antes o después del controlador y acción.
        - Servicio y método que se ejecutará para el controlador y acción.
        - Orden en que se ejecutarán estos métodos.

2. Instalación
--------------

    1. Copiar el componente dentro de la carpeta `vendor/boson/aspect-bundle/UCI/Boson`.
    2. Registrarlo en el archivo `app/autoload.php` de la siguiente forma:

       .. code-block:: php

           // ...
           $loader = require __DIR__ . '/../vendor/autoload.php';
           $loader->add("UCI\\Boson\\AspectBundle", __DIR__ . '/../vendor/boson/aspect-bundle');
           // ...

    3. Activarlo en el kernel de la siguiente manera:

       .. code-block:: php

           // app/AppKernel.php
           public function registerBundles()
           {
               return array(
                   // ...
                   new UCI\Boson\AspectBundle\AspectBundle(),
                   // ...
               );
           }

3. Especificación funcional
---------------------------

3.1. Requisitos funcionales
~~~~~~~~~~~~~~~~~~~~~~~~~~~

3.1.1. Cargar la configuración de los aspectos
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    - Primeramente se deben crear las clases de los servicios que contendrán los métodos y acciones a ejecutar antes o después de un controlador.
    - Luego se procede a crear los correspondientes servicios de las clases antes creadas.
    - Luego se configura el archivo `aspects.yml` o `aspects.xml` (según el tipo que sea) ubicado en la dirección `MyBundle/Resources/config`.

3.1.2. Ejecutar aspectos antes de un controlador
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    Para ejecutar algún aspecto antes de un controlador usted deberá especificarlo en el archivo de configuración
    el cual se explica más adelante.

3.1.3. Ejecutar aspectos después de un controlador
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    Para ejecutar algún aspecto después de un controlador usted deberá especificarlo en el archivo de configuración
    el cual se explica más adelante.

3.2. Configuración del archivo de configuración aspects.yml
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    El fichero `aspects.yml` debe quedar de la siguiente forma:

    .. code-block:: text

        aspects:
            aspects:
                .......

    El fichero `aspects.xml` debe quedar de la siguiente forma:

    .. code-block:: xml

        <?xml version="1.0" encoding="UTF-8"?>
                <aspects>
                    <aspects>
                        ........
                    </aspects>
                </aspects>

3.3. Agregar nuevos aspectos al archivo de configuración
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    Los aspectos agregados al archivo tienen los siguientes parámetros de configuración además del nombre que lo identifica:
        - `controller_action`: Indica el controlador y acción al que está asociado este aspecto con formato **Controller:Action**.
        - `type`: Indica en qué momento se ejecutará este aspecto **pre** en caso de ser antes y **post** en caso de ser después.
        - `service_name`: Nombre del servicio del aspecto.
        - `method`: Método del servicio del aspecto.
        - `order`: Orden en que se ejecutará este aspecto con respecto a los demás, en caso de especificarse -1 dicho aspecto se ejecutará al final.

    A continuación se muestra un ejemplo de cómo quedaría la configuración de los aspectos para ambos formatos (yml y xml).

        Formato yml:

        .. code-block:: yml

            aspects:
                aspects:
                    nombre_aspecto1:
                        controller_action: DefaultController:indexAction
                        type: pre
                        service_name: cache.aspect
                        method: metodo1
                        order: 1

        Formato xml:

        .. code-block:: xml

            <?xml version="1.0" encoding="UTF-8"?>
            <aspects>
                <aspects>
                    <nombre_aspecto1>
                        <controller_action>
                            DefaultController:indexAction
                        </controller_action>
                        <type>
                            pre
                        </type>
                        <service_name>
                            cache.aspect
                        </service_name>
                        <method>
                            metodo1
                        </method>
                        <order>
                            1
                        </order>
                    </nombre_aspecto1>
                </aspects>
            </aspects>

3.4. Configurando el componente a través de la interfaz gráfica
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
3.4.1. Adicionar aspectos por componentes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Para adicionar nuevos aspectos es necesario acceder a la opción Adicionar, dentro del menú de Aspectos. Esta acción muestra una pantalla con los elementos para construir un nuevo aspecto, siendo obligatorio especificarlos todos para activar el botón de adición. Luego de especificados estos campos y presionado el botón de adición se pide una confirmación para adicionar el aspecto. Al indicar que sí se desea adicionar el aspecto, se muestra el mensaje indicando el éxito de la operación.

3.4.2. Modificar aspectos por componentes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Para modificar algunos de estos aspectos se debe acceder a la opción Modificar en el menú lateral. El combo box Bundles muestra solo los bundles con aspectos ya especificados. En dependencia del bundle que se seleccione se muestran en el combo box de aspecto un conjunto de aspectos para ese bundle. Para modificar alguno de estos aspectos solo basta con seleccionarlo y modificar el campo deseado en el segmento inferior de la ventana. El usuario debe presionar el botón Modificar. El sistema pide una confirmación antes de realizar la acción. Al seleccionar que si se desea modificar el aspecto se muestra un mensaje indicando el éxito de la operación: “El aspecto se ha modificado satisfactoriamente”.

3.4.3. Eliminar aspectos por componentes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Para eliminar alguno de estos aspectos se debe acceder a la opción Eliminar en el menú lateral. Luego se debe especificar a que bundle pertenece el aspecto en cuestión, y el nombre del mismo. Una vez en este paso se activa el botón Eliminar. Una vez presionado el botón se pide al usuario la confirmación para realizar los cambios. Al presionar que si se desea eliminar el aspecto se muestra un mensaje indicando el éxito de la operación.


3.5. Request y Response de los controladores
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    Si usted desea trabajar dentro de su aspecto con el **Request** y **Response** del controlador ejecutado,
    usted debe extender de la clase *UCI\\Boson\\AspectBundle\\AspectClasses\\Aspect.php*.
    Para obtenerlos solo deberá llamar a los métodos *getRequest()* y *getResponse()*.

4. Eventos observados
---------------------

    .. code-block:: php

        onKernelController(FilterControllerEvent $event)

    - Este evento es el que controla cuando se empieza a ejecutar un controlador.

    .. code-block:: php

        onKernelResponse(FilterResponseEvent $event)

    - Este evento es el que controla cuando se termina de ejecutar un controlador.

---------------------------------------------

:Versión: 1.0 17/7/2015
:Autores: Julio Cesar Ocaña Bermúdez jcocana@uci.cu,
          Daniel Herrera Sánchez dherrera@estudiantes.uci.cu

Contribuidores
--------------

:Entidad: Universidad de las Ciencias Informáticas. Centro de Informatización de Entidades.


Licencia
--------
