<?php

namespace UCI\Boson\AspectBundle\Controller;

use UCI\Boson\BackendBundle\Controller\BackendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BackendController
{
    /**
     * @Route(path="/aspect/admin/scripts/config.aspect.js", name="aspect_app_config")
     */
    public function getAppAction()
    {
        return $this->jsResponse('AspectBundle:Scripts:config.js.twig');
    }

}
