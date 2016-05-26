<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/05/15
 * Time: 8:56
 */

namespace UCI\Boson\AspectBundle\Tests\AspectClasses;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AspectTest extends \PHPUnit_Framework_TestCase
{
    private static $aspect;

    public static function setUpBeforeClass()
    {
        self::$aspect = new Aspecto();
        self::$aspect->setRequest(new Request());
        self::$aspect->setResponse(new Response());
    }

    public function testgetRequest()
    {
        $request = self::$aspect->getRequest();
        $this->assertEquals($request instanceof Request, true);
    }

    public function testsetRequest()
    {
        self::$aspect->setRequest(new Request());
        $this->assertNotNull(self::$aspect->getRequest());
        $this->assertEquals(self::$aspect->getRequest() instanceof Request, true);

    }

    public function testgetResponse()
    {
        $response = self::$aspect->getResponse();
        $this->assertEquals($response instanceof Response, true);
    }

    public function testsetResponse()
    {
        self::$aspect->setResponse(new Response());
        $this->assertNotNull(self::$aspect->getResponse());
        $this->assertEquals(self::$aspect->getResponse() instanceof Response, true);
    }

}
