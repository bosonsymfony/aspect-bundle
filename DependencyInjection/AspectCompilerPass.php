<?php
/**
 * Created by PhpStorm.
 * User: abs
 * Date: 15-10-2014
 * Time: 11:28 PM
 */

namespace UCI\Boson\AspectBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AspectCompilerPass implements CompilerPassInterface {

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        // TODO: Implement process() method.

        if (!$container->hasDefinition("aspect_interceptor")) {
            return;
        }

        $definition = $container->getDefinition("aspect_interceptor");
        $taggedServices = $container->findTaggedServiceIds("aspect.filter");
    ladybug_dump("TEST");
        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'run',
                array(new Reference($id))
            );
        }
    }
}