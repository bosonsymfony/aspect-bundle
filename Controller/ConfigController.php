<?php

namespace UCI\Boson\AspectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UCI\Boson\BackendBundle\Controller\BackendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use UCI\Boson\AspectBundle\Entity\Data;
use UCI\Boson\AspectBundle\Form\DataType;


class ConfigController extends BackendController
{

    /**
     * Obtiene el token para que los formularios de angular trabajen.
     *
     * @Route("/aspects/csrf_token", name="aspects_csrf_form", options={"expose"=true})
     * @Method("POST")
     */
    public function getCsrfTokenAction(Request $request){
        $tokenId = $request->request->get('id_form');
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken($tokenId);
        return new Response($token);
    }

    /**
     * Devuelve la dirección de un bundle dado su nombre
     * @param string $nbund
     * @return string
     */
    public function getDirByBundle($nbund)
    {
        $dumper = new Dumper();
        $bundles = $this->container->get('kernel')->getBundles();
        foreach ($bundles as $b) {
            if ($b->getName() == $nbund) {
                $dir = $b->getPath() . '/Resources/config/aspects.yml';
                if (file_exists($dir)) {
                    return $b->getPath() . '/Resources/config/aspects.yml';
                } else {
                    $yaml_new['aspects']['aspects'] = null;
                    $yaml_dump = $dumper->dump($yaml_new, 6);
                    file_put_contents($dir, $yaml_dump);
                    return $b->getPath() . '/Resources/config/aspects.yml';
                }
            }
        }
    }

    /**
     * Devuelve los aspectos dentro de un archivo dada su dirección
     * @param string $dir
     * @return mixed
     */
    public function getAspectByDir($dir = null)
    {
        $yaml = new Parser();
        $values = $yaml->parse(file_get_contents($dir));
        return $values;
    }


    /**
     * Comprueba si existe un aspecto dado su nombre y la dirección del archivo
     * @param string $dir
     * @param string $nombreAspecto
     * @return bool
     */
    public function existAspect($dir = null, $nombreAspecto = null)
    {
        $contenido = $this->getAspectByDir($dir);

        if (!empty($contenido['aspects']['aspects'][$nombreAspecto])) {
            // si no esta vacio, quiere decir que ya hay un aspecto en uso con ese nombre en ese componente
            return true;
        } else {
            return false;
        }
    }


    /**
     * Devuelve el contenido del aspecto dado su nombre y el bundle al que pertenece
     * @Route(path="/aspects/getDataAspectByBundleAspect/{bundle}/{nombreAspecto}", name="aspects_get_dataAspect", options={"expose"=true})
     * @param string $bundle
     * @param string $nombreAspecto
     * @return JsonResponse
     */
    public function getDataAspectByBundleAspect($bundle = null, $nombreAspecto = null)
    {
        $dir = $this->getDirByBundle($bundle);
        $aspects = $this->getAspectByDir($dir);
        return new JsonResponse($aspects['aspects']['aspects'][$nombreAspecto]);
    }


    /**
     * Devuelve los aspectos asociados a un bundle
     * Responde a los RF(92-93) Buscar y Listar aspectos por componente
     * @Route(path="/aspects/getNombAspect/{bundle}", name="aspects_get_nombAspect", options={"expose"=true})
     * @param string $bundle
     * @return JsonResponse
     */
    public function getNombAspectByBundle($bundle = null)
    {
        $dir = $this->getDirByBundle($bundle);
        $values = $this->getAspectByDir($dir);
        $values_sn_front = $values['aspects']['aspects'];

        $response = array();

        if ($values_sn_front != null) {
            foreach ($values_sn_front as $key => $vf) {
                array_push($response, $key);
            }
        }

        return new JsonResponse($response);
    }

    /**
     * Devuelve un listado con los bundles que tienen aspectos especificados
     * @return JsonResponse
     * @Route(path="/aspects/getBundlesWithAspects", name="aspects_bundles_withaspects", options={"expose"=true})
     */
    public function getBundlesWithAspects()
    {
        $coleccion = array();
        $bundles = $this->container->get('kernel')->getBundles();

        foreach ($bundles as $b) {

            if (file_exists($b->getPath() . '/Resources/config/aspects.yml')) {

                $tmp_aspect = $this->getAspectByDir($this->getDirByBundle($b->getName()));
                if (!empty($tmp_aspect['aspects']['aspects'])) {
                    $datos = array(
                        'NombreBundle' => $b->getName(),
                    );
                    array_push($coleccion, $datos);
                }
            }
        }
        return new JsonResponse($coleccion);

    }

    /**
     * Devuelve un listado con los bundles registrados en la aplicación
     * @return JsonResponse
     * @Route(path="/aspects/getInfo", name="aspects_get_info", options={"expose"=true})
     */
    public function getInfo()
    {
        $coleccion = array();
        $bundles = $this->container->get('kernel')->getBundles();

        foreach ($bundles as $b) {
            $datos = array(
                'NombreBundle' => $b->getName(),
            );
            array_push($coleccion, $datos);
        }
        return new JsonResponse($coleccion);
    }

    /**
     * Adiciona nuevos aspectos al componente especificado
     * Responde al RF(89) Adicionar aspecto por componente
     * @Route(path="/aspect/saveData", name="aspect_save_data", options={"expose"=true})
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function writeYAMLAction(Request $request)
    {
        $data = new Data();
        $dumper = new Dumper();
        $form = $this->createForm(new DataType(), $data);
        $form->handleRequest($request);

        $dir = $this->getDirByBundle($data->getBundle());

        if ($this->existAspect($dir, $data->getNombreAspecto())) {
            return new Response("El nombre especificado se encuentra en uso por otro aspecto.", 500);
        }

        $yaml = $this->getAspectByDir($dir);

        if ($form->isValid()) {
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['controller_action'] = $data->getControllerAction();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['type'] = $data->getType();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['service_name'] = $data->getServiceName();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['method'] = $data->getMethod();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['order'] = intval($data->getOrder());
        }

        $yaml_dump = $dumper->dump($yaml, 6);
        file_put_contents($dir, $yaml_dump);
        return new Response("El aspecto ha sido insertado  satisfactoriamente.", 200);
    }

    /**
     * Funcionalidad que permite modificar los aspectos de un bundle
     * Responde al RF(90) Modificar aspectos por componente
     * @Route(path="/aspect/modifyData", name="aspect_modify_data", options={"expose"=true})
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function ModificarDatos(Request $request)
    {
        $data = new Data();
        $form = $this->createForm(new DataType(), $data);
        $form->handleRequest($request);

        $dir = $this->getDirByBundle($data->getBundle());

        $yaml = $this->getAspectByDir($dir);

        if ($data->getNombreAspecto() !== $data->getNombreAspectoAnterior()) {
            if ($this->existAspect($dir, $data->getNombreAspecto())) {
                return new Response("El nombre especificado se encuentra en uso por otro aspecto.", 500);
            }
        }

        $dumper = new Dumper();

        if ($form->isValid()) {
            unset($yaml['aspects']['aspects'][$data->getNombreAspectoAnterior()]);
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['controller_action'] = $data->getControllerAction();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['type'] = $data->getType();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['service_name'] = $data->getServiceName();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['method'] = $data->getMethod();
            $yaml['aspects']['aspects'][$data->getNombreAspecto()]['order'] = intval($data->getOrder());
        }

        $yaml_dump = $dumper->dump($yaml, 6);
        file_put_contents($dir, $yaml_dump);
        return new Response("El aspecto ha sido modificado satisfactoriamente.", 200);
    }


    /**
     * Funcionalidad que permite eliminar los aspectos de un componente
     * Responde al RF(91) Eliminar aspecto del componente
     * @Route(path="/aspect/eraseAspect/{bundle}/{nombreAspecto}", name="aspect_erase_data", options={"expose"=true})
     * @param string $bundle
     * @param string $nombreAspecto
     * @return Response
     */
    public function BorrarAspecto($bundle, $nombreAspecto)
    {

        $dumper = new Dumper();

        $dir = $this->getDirByBundle($bundle);
        $yaml = $this->getAspectByDir($dir);

        //borrar la entrada completa de aspectos, incluyendo el nombre
        unset($yaml['aspects']['aspects'][$nombreAspecto]);

        $yaml_dump = $dumper->dump($yaml, 6);
        file_put_contents($dir, $yaml_dump);

        //para eliminar ese { } molesto q se qda cdo se eliminan todos los aspectos
        $yaml_new = $this->getAspectByDir($dir);
        if (empty($yaml_new['aspects']['aspects'])) {
//            Una variante que funciona. deja un null luego del segundo aspect
            $yaml_new['aspects']['aspects'] = null;
            $yaml_dump = $dumper->dump($yaml_new, 6);
            file_put_contents($dir, $yaml_dump);
        }
        return new Response("El aspecto ha sido eliminado satisfactoriamente.", 200);
    }

}


