<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/05/15
 * Time: 10:29
 */

namespace UCI\Boson\AspectBundle\Tests\AspectClasses;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use UCI\Boson\AspectBundle\AspectClasses\ControllerPointcut;

if(!strpos(__DIR__,"/vendor/boson/")){
    require_once __DIR__ . '/../../../../../../app/AppKernel.php';
}
else{
    require_once __DIR__ . '/../../../../../../../../app/AppKernel.php';
}
class ControllerPointcutTest extends \PHPUnit_Framework_TestCase
{
    private static $controller;
    private static $controllerPointcut;
    protected static $kernel;
    protected static $container;
    static $accionListener;

    public static function setUpBeforeClass()
    {
        self::$kernel = new \AppKernel('dev', true);
        self::$kernel->boot();
        self::$container = self::$kernel->getContainer();
        self::$controller = "UCI\\Boson\\AspectBundle\\Tests\\Controller\\TestController::indexAction";
        self::$controllerPointcut = new ControllerPointcut(self::$container);

    }


    public function testgetController()
    {
        $refClass = new \ReflectionClass(self::$controllerPointcut);
        $method = $refClass->getMethod('getController');
        $method->setAccessible(true);
        $controller = $method->invokeArgs(self::$controllerPointcut, array(self::$controller));

        $this->assertEquals("TestController", $controller);

    }

    public function testgetAction()
    {
        $refClass = new \ReflectionClass(self::$controllerPointcut);
        $method = $refClass->getMethod('getAction');
        $method->setAccessible(true);
        $action = $method->invokeArgs(self::$controllerPointcut, array(self::$controller));

        $this->assertEquals("indexAction", $action);

    }

    public function testgetBundle()
    {
        $refClass = new \ReflectionClass(self::$controllerPointcut);
        $method = $refClass->getMethod('getBundle');
        $method->setAccessible(true);
        $bundle = $method->invokeArgs(self::$controllerPointcut, array(self::$controller));

        $this->assertEquals("AspectBundle", $bundle);

    }

    public function testunderscore()
    {
        $refClass = new \ReflectionClass(self::$controllerPointcut);
        $method = $refClass->getMethod('underscore');
        $method->setAccessible(true);
        $under = $method->invokeArgs(self::$controllerPointcut, array("AspectBundle"));

        $this->assertEquals("aspect_bundle", $under);

    }

    public function testonKernelController()
    {
        $request = new Request();
        $request->attributes->set('_controller', self::$controller);
        $event = new FilterControllerEvent(self::$kernel, self::$controller, $request, 'Request');
        try {
            self::$controllerPointcut->onKernelController($event);
        } catch (\Exception $e) {
            $this->assertStringStartsWith('El aspecto "nombre_aspecto1" del bundle "AspectBundle" no se cumplió', $e->getMessage());
        }

    }

    public function testonKernelResponse()
    {
        $request = new Request();
        $request->attributes->set('_controller', self::$controller);
        $event = new FilterResponseEvent(self::$kernel, $request,'Request',new Response());
        $this->assertTrue(self::$controllerPointcut->onKernelResponse($event));
    }


}
