<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Command;

use Jonathankablan\Bundle\FastEntityBundle\Configuration\SettingManager;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MagicEntityCommand extends Command
{
    protected static $defaultName = 'magic:entity';

    protected function configure()
    {
        $this
            ->setDescription('Generate all config entity')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Generator $generator
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output, Generator $generator): int
    {
        $io = new SymfonyStyle($input, $output);

        $setting = new SettingManager();
        dump($setting->readYamlConfig());

        $generator->generateClass(
            $commandClassNameDetails->getFullName(),
            'command/Command.tpl.php',
            [
                'command_name' => $commandName,
            ]
        );

        $generator->writeChanges();

        $io->success('You have a new command!');

        return 0;
    }
}
