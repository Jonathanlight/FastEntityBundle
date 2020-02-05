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
        $this->addUserSection($rootNode);
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

                ->variableNode('disabled_actions')
                    ->info('The names of the actions disabled for all backend entities.')
                    ->defaultValue([])
                    ->validate()
                        ->ifTrue(function ($v) {
                            return false === \is_array($v);
                        })
                        ->thenInvalid('The disabled_actions option must be an array of action names.')
                    ->end()
                ->end()

                ->scalarNode('translation_domain')
                    ->validate()
                        ->ifTrue(function ($v) {
                            return '' === $v;
                        })
                        ->thenInvalid('The translation_domain option cannot be an empty string (use false to disable translations).')
                    ->end()
                    ->defaultValue('messages')
                    ->info('The translation domain used to translate the main menu and the labels, titles and help messages of all entities.')
                ->end()
            ->end()
        ;
    }

    private function addUserSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('user')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('display_name')
                            ->defaultTrue()
                            ->info('If true, the user name is displayed in the logged user section.')
                        ->end()

                        ->booleanNode('display_avatar')
                            ->defaultTrue()
                            ->info('If true, the user avatar image is displayed in the logged user section.')
                        ->end()

                        ->scalarNode('name_property_path')
                            ->defaultValue('__toString')
                            ->info('A valid PropertyPath expression used to get the value of the user name (by default, __toString() is used).')
                        ->end()

                        ->scalarNode('avatar_property_path')
                            ->defaultNull()
                            ->info('A valid PropertyPath expression used to get the value of the avatar image path which is used as the "src" attribute of the <img> element.')
                        ->end()
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
