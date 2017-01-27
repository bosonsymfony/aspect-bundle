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
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $this->loadFileAspects($container);
    }

    public function loadFileAspects(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $aspectsConfig = array(
            'pre' => array(),
            'post' => array()
        );
        foreach ($bundles as $index => $bundle) {

            $refClass = new \ReflectionClass($bundle);
            $bundleDir = dirname($refClass->getFileName());
            $dirConfig = $bundleDir . '/Resources/config';
            $locator = new FileLocator($dirConfig);

            try {
                if (file_exists($dirConfig . DIRECTORY_SEPARATOR . "aspects.yml")) {
                    $loader = new YamlFileLoader($container, $locator);
                    $locator->locate("aspects.yml");
                    $configs = $loader->load('aspects.yml');
                } elseif (file_exists($dirConfig . DIRECTORY_SEPARATOR . "aspects.xml")) {
                    $loader = new XmlFileLoader($container, $locator);
                    $locator->locate("aspects.xml");
                    $configs["aspects"] = $loader->load('aspects.xml');
                } else {
                    continue;
                }
            } catch (\InvalidArgumentException $exc) {
                throw $exc;
            }
            $configuration = new AspectConfiguration();
            $aspects = $this->processConfiguration($configuration, $configs)['aspects'];
            foreach ($aspects as $name => $aspect) {
                $element = array(
                    'service_name' => $aspect['service_name'],
                    'method' => $aspect['method'],
                    'order' => $aspect['order'],
                );
                $aspectsConfig[$aspect['type']][$this->getController($bundle, $aspect['controller_action'])][$name] = $element;
            }
        }
        foreach ($aspectsConfig['pre'] as $index => $item) {
            $aspectsConfig['pre'][$index] = $this->createOrderedList($item);
        }
        foreach ($aspectsConfig['post'] as $index => $item) {
            $aspectsConfig['post'][$index] = $this->createOrderedList($item);
        }
        $container->setParameter("uci_boson_aspects", $aspectsConfig);
    }

    private function createOrderedList($config)
    {
        $aspects = $config;
        uasort($aspects, function ($a, $b) {
            if ($a['order'] == -1 || $b['order'] == -1) {
                return !($a['order'] > $b['order']);
            }
            return $a['order'] > $b['order'];
        });
        return $aspects;
    }

    private function getController($bundleNamespace, $controllerAction)
    {
        $array = explode("\\", $bundleNamespace);
        $controller = "";
        for ($i = 0; $i < count($array) - 1; $i++) {
            $controller .= $array[$i] . "\\";
        }
        $controller .= "Controller\\" . str_replace(":", "::", $controllerAction);
        return $controller;
    }
}
