<?php

namespace App\Command\Actions;

use App\Command\AbstractSQLCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateActionsCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:generate:actions';

    protected function configure()
    {
        $this
            ->setDescription('Generate actions for all characters')
            ->addArgument('start', InputArgument::OPTIONAL, 'First day')
            ->addArgument('end', InputArgument::OPTIONAL, 'Last day')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Actions generator',
            '============',
            '',
        ]);

        $start = $input->getArgument('start');
        $end = $input->getArgument('end');

        $statement = $this->connection->executeQuery('SELECT generateActionFromDayToDay(\''.$start.'\', \''.$end.'\')');
        $statement->fetchAll();

        $io->success('Actions generated!');
    }
}
