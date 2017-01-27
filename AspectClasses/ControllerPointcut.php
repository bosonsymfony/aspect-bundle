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
class ControllerPointcut
{
    /**
     * El contenedor de servicios.
     *
     * @var mixed
     */
    private $container;

    /**
     * @var array
     */
    private $aspectsConfig;


    /**
     * Constructor de la clase
     *
     * @param Container $container
     * @param $aspectsConfig
     */
    function __construct(Container $container, $aspectsConfig)
    {
        $this->container = $container;
        $this->aspectsConfig = $aspectsConfig;
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
    public function onKernelController(FilterControllerEvent $event)
    {
        $absolutePath = $event->getRequest()->attributes->get('_controller');
        if (array_key_exists($absolutePath, $this->aspectsConfig['pre'])) {
            $aspects = $this->aspectsConfig['pre'][$absolutePath];
            foreach ($aspects as $key => $valor) {
                $class = $this->container->get($valor['service_name']);
                if ($class instanceof Aspect) {
                    $class->setRequest($event->getRequest());
                }
                $method = $valor['method'];
                $value = $class->$method();
                if (!$value) {
                    throw new \Exception("El aspecto \"$key\" no se cumplió");
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
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $absolutePath = $event->getRequest()->attributes->get('_controller');
        if (array_key_exists($absolutePath, $this->aspectsConfig['post'])) {
            $aspects = $this->aspectsConfig['post'][$absolutePath];
            foreach ($aspects as $valor) {
                $class = $this->container->get($valor['service_name']);
                if ($class instanceof Aspect) {
                    $class->setResponse($event->getResponse());
                }
                $method = $valor['method'];
                $class->$method();
            }
        }
        return true;
    }
}