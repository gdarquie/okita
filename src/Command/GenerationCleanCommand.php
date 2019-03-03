<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerationCleanCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:generate:clean';

    protected function configure()
    {
        $this
            ->setDescription('Clean generation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $statement = $this->connection->executeQuery('SELECT clean()');
        $statement->fetchAll();

        $io->success('Clean finished');
    }
}
