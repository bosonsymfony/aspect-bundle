<?php
namespace UCI\Boson\AspectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Esta clase valida la configuración definida en tu Resources/config/aspects.[yml, xml]
 *
 * @author Julio Cesar Ocaña bermudez <jcocana@uci.cu>
 */
class AspectConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('aspects')
            ->children()
                ->arrayNode('aspects')
                ->isRequired()
                //->requiresAtLeastOneElement()
                ->disallowNewKeysInSubsequentConfigs()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('controller_action')->end()
                        ->scalarNode('type')->end()
                        ->scalarNode('service_name')->end()
                        ->scalarNode('method')->end()
                        ->scalarNode('order')->end()
                ->end()
                ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
