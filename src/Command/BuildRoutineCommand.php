<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BuildRoutineCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:build:routine';

    protected function configure()
    {
        $this
            ->setDescription('Command for creating routine')
            ->addArgument('name', InputArgument::OPTIONAL, 'Routine name')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content of the routine')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $content = $input->getArgument('content');

        $statement = $this->connection->executeQuery('SELECT build_routine(\''.$name.'\', \''.$content.'\')');
        $statement->fetchAll();
    }
}
