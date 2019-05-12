<?php

namespace App\Command;

use App\Service\RoutineGeneratorService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateScheduleCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:generate:schedule';

    protected function configure()
    {
        $this
            ->setDescription('Create a json output for build_routine SQL function')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $routineService = new RoutineGeneratorService($this->em);
        $result = $routineService->createRoutine();
        
        $io->success('Voilà!');
        $output->writeln($result);
    }
}
