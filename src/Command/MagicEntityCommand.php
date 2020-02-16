<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('You have a new command!');

        return 0;
    }
}
