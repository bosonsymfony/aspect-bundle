<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/05/15
 * Time: 11:20
 */

namespace UCI\Boson\AspectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{

    public function indexAction()
    {
        return new Response('Prueba');
    }
}