<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Config\FileLocator;

class MagicEntityCommand extends Command
{
    const FAST_ENTITY = 'fast_entity.yaml';

    protected $configDirectories = [__DIR__.'/config'];

    protected $yamlFiles;

    protected static $defaultName = 'magic:entity';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $fileLocator = new FileLocator($this->configDirectories);
        $this->yamlFiles = $fileLocator->locate(self::FAST_ENTITY, null, false);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        dump($this->yamlFiles);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
