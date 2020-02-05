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

        $this->addGlobalOptionsSection($rootNode);
        $this->addEntitiesSection($rootNode);

        return $treeBuilder;
    }

    private function addGlobalOptionsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('name')
                    ->defaultValue('FastEntity')
                    ->info('Welcome to FastEntityBundle.')
                ->end()

                ->arrayNode('formats')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('date')
                        ->defaultValue('Y-m-d')
                        ->info('The PHP date format applied to "date" and "date_immutable" field types.')
                        ->example('d/m/Y (see http://php.net/manual/en/function.date.php)')
                    ->end()

                    ->scalarNode('time')
                        ->defaultValue('H:i:s')
                        ->info('The PHP time format applied to "time" and "time_immutable" field types.')
                        ->example('h:i a (see http://php.net/date)')
                    ->end()

                    ->scalarNode('datetime')
                        ->defaultValue('F j, Y H:i')
                        ->info('The PHP date/time format applied to "datetime" and "datetime_immutable" field types.')
                        ->example('l, F jS Y / h:i (see http://php.net/date)')
                    ->end()

                    ->scalarNode('number')
                        ->info('The sprintf-compatible format applied to numeric values.')
                        ->example('%.2d (see http://php.net/sprintf)')
                    ->end()

                    ->scalarNode('dateinterval')
                        ->defaultValue('%%y Year(s) %%m Month(s) %%d Day(s)')
                        ->info('The PHP dateinterval-compatible format applied to "dateinterval" field types.')
                        ->example('%%y Year(s) %%m Month(s) %%d Day(s) (see http://php.net/manual/en/dateinterval.format.php)')
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addEntitiesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('entities')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('name', false)
                    ->defaultValue([])
                    ->info('The list of entities to manage in the administration zone.')
                    ->prototype('variable')
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
