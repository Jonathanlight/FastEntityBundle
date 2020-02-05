<?php

namespace Jonathankablan\Bundle\FastEntityBundle;

use Jonathankablan\Bundle\FastEntityBundle\DependencyInjection\Compiler\FastEntityConfigPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Jonathan Kablan <jonathan.kablan@gmail.com>
 */
class FastEntityBundle extends Bundle
{
    public const VERSION = '2.0.0-DEV';

    public function boot()
    {
        // noop
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FastEntityConfigPass());
    }

    public function registerCommands(Application $application)
    {
        // noop
    }
}