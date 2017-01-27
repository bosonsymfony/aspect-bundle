<?php

namespace UCI\Boson\AspectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Data
 */
class Data
{

    /**
     * @var string
     *
     */
    private $bundle;

    /**
     * @var string
     *
     */
    private $nombreAspecto;

    /**
     * @var string
     *
     */
    private $nombreAspectoAnterior;


    /**
     * @var string
     *
     */
    private $controllerAction;

    /**
     * @var string
     *
     */
    private $type;

    /**
     * @var string
     *
     */
    private $serviceName;

    /**
     * @var string
     *
     */
    private $method;

    /**
     * @var integer
     *
     */
    private $order;


    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @return string
     */
    public function getNombreAspecto()
    {
        return $this->nombreAspecto;
    }

    /**
     * @param string $nombreAspecto
     */
    public function setNombreAspecto($nombreAspecto)
    {
        $this->nombreAspecto = $nombreAspecto;
    }

    /**
     * @return string
     */
    public function getNombreAspectoAnterior()
    {
        return $this->nombreAspectoAnterior;
    }

    /**
     * @param string $nombreAspectoAnterior
     */
    public function setNombreAspectoAnterior($nombreAspectoAnterior)
    {
        $this->nombreAspectoAnterior = $nombreAspectoAnterior;
    }

    /**
     * Set controllerAction
     *
     * @param string $controllerAction
     *
     * @return Data
     */
    public function setControllerAction($controllerAction)
    {
        $this->controllerAction = $controllerAction;

        return $this;
    }

    /**
     * Get controllerAction
     *
     * @return string
     */
    public function getControllerAction()
    {
        return $this->controllerAction;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Data
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set serviceName
     *
     * @param string $serviceName
     *
     * @return Data
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Get serviceName
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set method
     *
     * @param string $method
     *
     * @return Data
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return Data
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
}

