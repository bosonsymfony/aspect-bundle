<?php


namespace UCI\Boson\AspectBundle\AspectClasses;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Clase abstracta  de las que heredan las implementaciones de aspectos.
 *
 * @since  1.0
 * @author Julio Cesar Ocana <jcocana@uci.cu>
 *
 */
abstract class Aspect {

    /**
     * Objeto Response.
     *
     * @var  Response
     */
    private $response;

    /**
     * Objeto Request.
     *
     * @var Request
     */
    private $request;

    /**
     * Devuelve el objeto Request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Establece el objeto Request.
     *
     * @param Request $request
     *
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Devuelve el objeto Response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Establece el objeto Response.
     *
     * @param Response $response
     *
     * @return void
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

} 