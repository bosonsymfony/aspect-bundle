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
        $rootNode =
            $treeBuilder->root('aspects')
                ->children()
                    ->arrayNode('aspects')->isRequired()
                        ->prototype('array')
                            ->children()
                                ->scalarNode('controller_action')->end()
                                ->enumNode('type')->values(array('pre', 'post'))->end()
                                ->scalarNode('service_name')->end()
                                ->scalarNode('method')->end()
                                ->integerNode('order')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
