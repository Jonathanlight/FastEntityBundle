<?php

namespace Jonathankablan\Bundle\FastEntityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Jonathan Kablan <jonathan.kablan@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('fast_entity');
        $rootNode = $this->getRootNode($treeBuilder, 'fast_entity');

        $this->addSchemaSection($rootNode);
        $this->addRelationOptionSection($rootNode);

        return $treeBuilder;
    }

    private function addSchemaSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('schema')
            ->children()
                ->arrayNode('schemas')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('entity')->end()
                            ->scalarNode('property')->end()
                            ->scalarNode('type')->end()
                            ->integerNode('length')
                                ->min(0)->max(255)
                            ->end()
                            ->booleanNode('nullable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    private function addRelationOptionSection(ArrayNodeDefinition $rootNode)
    {
       // entityTo entityFrom relation
        $rootNode
            ->fixXmlConfig('relation')
            ->children()
                ->arrayNode('relations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('entityTo')->end()
                            ->scalarNode('entityFrom')->end()
                            ->scalarNode('relation')
                                ->isRequired()
                                ->validate()
                                    ->ifNotInArray(['OneToOne', 'OneToMany', 'ManyToOne', 'ManyToMany', null])
                                    ->thenInvalid('Invalid relation %s')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function getRootNode(TreeBuilder $treeBuilder, $name)
    {
        // BC layer for symfony/config 4.1 and older
        if (!method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->root($name);
        }

        return $treeBuilder->getRootNode();
    }
}
