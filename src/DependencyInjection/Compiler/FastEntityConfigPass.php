<?php

namespace Jonathankablan\Bundle\FastEntityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Jonathan Kablan <jonathan.kablan@gmail.com>
 */
final class FastEntityConfigPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configPasses = $this->findAndSortTaggedServices('fastentity.config_pass', $container);
        /*$definition = $container->getDefinition('fastentity.config.manager');

        foreach ($configPasses as $service) {
            $definition->addMethodCall('addConfigPass', [$service]);
        }*/
    }
}
