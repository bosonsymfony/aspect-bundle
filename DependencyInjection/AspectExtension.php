<?php

namespace UCI\Boson\AspectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use UCI\Boson\AspectBundle\Loader\YamlFileLoader;
use UCI\Boson\AspectBundle\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AspectExtension extends Extension
{
    private $direccionBundle;
    private $nameBundle;

    function __construct($direccionBundle = "", $nameBundle = "")
    {
        $this->direccionBundle = $direccionBundle;
        $this->nameBundle = $nameBundle;
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        //print_r(__DIR__.'/../Resources/config ');
        $loader->load('services.yml');
        $this->loadFileAspects($container);
    }

    public function loadFileAspects(ContainerBuilder $container)
    {
        if ($this->direccionBundle == "" || $this->nameBundle == "")
            $this->getDirBundle($container);

        $locator = new FileLocator($this->direccionBundle . '/Resources/config');

        try {
            $loader = new YamlFileLoader($container, $locator);

            $locator->locate("aspects.yml");
            $configs = $loader->load('aspects.yml');

        } catch (\InvalidArgumentException $exc) {
            try {
                $loader = new XmlFileLoader($container, $locator);
                $locator->locate("aspects.xml");
                $configs["aspects"] = $loader->load('aspects.xml');

            } catch (\InvalidArgumentException $exc) {
                throw $exc;
            }
        }
        $configuration = new AspectConfiguration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter("aspects_" . $container::underscore($this->nameBundle), $this->createOrdereList($config));
        //print_r("aspects_" . $container::underscore($this->nameBundle).' ');

    }


    private function getDirBundle($container)
    {
        $resources = $container->getResources();
        $arrayRutaFile = explode(DIRECTORY_SEPARATOR, $resources[0]);
        $this->direccionBundle = $arrayRutaFile[0];
        for ($i = 1; $i < count($arrayRutaFile); $i++) {
            $this->direccionBundle = $this->direccionBundle . DIRECTORY_SEPARATOR . $arrayRutaFile[$i];
            if (preg_match('/Bundle$/', $arrayRutaFile[$i]) == 1) {
                $this->nameBundle = $arrayRutaFile[$i];
                break;
            }
        }
    }


    private function createOrdereList($config)
    {
        $aspects = $config['aspects'];
        uasort($aspects, function ($a, $b) {
            if ($a['order'] == -1 || $b['order'] == -1) {
                return !($a['order'] > $b['order']);
            }
            return $a['order'] > $b['order'];
        });
        return $aspects;
    }


}
