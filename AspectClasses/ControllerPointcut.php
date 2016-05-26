<?php


namespace UCI\Boson\AspectBundle\AspectClasses;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Clase base encargada de interceptar la ejecución de un controlador.
 *
 * @since  1.0
 * @author Julio Cesar Ocana <jcocana@uci.cu>
 *
 */
class ControllerPointcut {

    /**
     * El contenedor de servicios.
     *
     * @var mixed
     */
    private $container;


    /**
     * Constructor de la clase
     *
     * @param Container $container
     */
    function __construct(Container $container)
    {
        $this->container = $container;

    }


    /**
     * Antes de ejecutar un controlador llama a todos los aspectos declarados de tipo pre.
     *
     * @param FilterControllerEvent $event
     *
     * @throws \Exception
     * @return void
     *
     */
    public function onKernelController(FilterControllerEvent $event){

        $controller = $event->getController();
        $absolutePath = $event->getRequest()->attributes->get('_controller');
        $bundle = $this->getBundle($absolutePath);
        $controller1 = $this->getController($absolutePath);
        $action = $this->getAction($absolutePath);
        $paramemter = "aspects_".$this->underscore($bundle);
        if ($this->container->hasParameter($paramemter)){
            $aspects = $this->container->getParameter($paramemter);
            foreach ($aspects as $key => $valor){
                if ($valor['controller_action'] == $controller1.':'.$action && $valor['type'] == 'pre'){
                    $class = $this->container->get($valor['service_name']);
                    if ($class instanceof Aspect){
                        $class->setRequest($event->getRequest());
                    }
                    $value = $class->$valor['method']();
                    if (!$value){
                        throw new \Exception("El aspecto \"$key\" del bundle \"$bundle\" no se cumplió");
                    }
                }
            }

        }
    }



    /**
     * Después de ejecutar un controlador llama a todos los aspectos declarados de tipo post.
     *
     * @param FilterResponseEvent $event
     *
     * @return boolean
     */
    public function onKernelResponse(FilterResponseEvent $event) {

        $response = $event->getResponse();

        $absolutePath = $event->getRequest()->attributes->get('_controller');
        $bundle = $this->getBundle($absolutePath);
        $controller = $this->getController($absolutePath);
        $action = $this->getAction($absolutePath);
        $paramemter = "aspects_".$this->underscore($bundle);
        if ($this->container->hasParameter($paramemter)){
            $aspects = $this->container->getParameter($paramemter);
            foreach ($aspects as $valor){
                if ($valor['controller_action'] == $controller.':'.$action && $valor['type'] == 'post'){
                    $class = $this->container->get($valor['service_name']);
                    if ($class instanceof Aspect){
                        $class->setResponse($response);
                    }
                    $this->container->get($valor['service_name'])->$valor['method']();
                }
            }
        }
        return true;
    }

    /**
     * Devuelve el nombre del controlador a partir de una dirección
     *
     * @param string $path
     *
     * @return string
     */
    private function getController($path){
        $array = explode('::', $path);
        $array1 = explode('\\',$array[0]);
        return $array1[count($array1) - 1];
    }

    /**
     * Devuelve el nombre de la acción a partir de una dirección
     *
     * @param string $path
     *
     * @return string
     */
    private function getAction($path){
        $array = explode('::', $path);
        return $array[count($array) - 1];
    }

    /**
     * Devuelve el bundle a partir de la dirección.
     *
     * @param string $path
     *
     * @return string
     */
    private function getBundle($path){
        $arrayRutaFile = explode('\\', $path);
        for ($i = 1; $i < count($arrayRutaFile); $i++) {
            if (preg_match('/Bundle$/',$arrayRutaFile[$i]) == 1) {
                return $arrayRutaFile[$i];
            }
        }
    }

    /**
     * Convierte un string a notación PascalCasing
     *
     * @param string $id
     *
     * @return string
     */
    private static function underscore($id)
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), strtr($id, '_', '.')));
    }

}