<?php

namespace App\Command;

use App\Service\RoutineGeneratorService;
use App\Service\SQLService;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class GenerateProjectCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:generate:project';

    protected function configure()
    {
        $this
            ->setDescription('Generate a new project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Project generation begins',
            '============',
            '',
        ]);

        // Set project path
        $rootPath = $this->container->get('kernel')->getProjectDir();
        $projectPath = $rootPath.'/src/Domain/Project';
        $projectFile = $projectPath.'/la-degradation.yaml';

        // Parse project config
        $value = Yaml::parseFile($projectFile);
        $totalCharacters = $value['characters']['total'];

        // Get all routines
        $routines = $this->collectRoutines($value['routines']);
        $io->success('Project pathes and config setted!');

        // Initialization
        $output->writeln([
            'Initialization is launched',
            '============',
            '',
        ]);
        (new SQLService($this->em))->initialize();
        $io->success('Initialization succeeds!');


        // Create Routines
        $buildRoutinesCommand = $this->getApplication()->find('app:build:routine');

        foreach ($routines as $key => $value) {

            $arguments = [
                'command' => 'app:build:routine',
                'name'    => $key,
                'content'  => $value,
            ];

            $buildRoutinesCommandInput = new ArrayInput($arguments);
            $buildRoutinesCommand->run($buildRoutinesCommandInput, $output);
        }

        //generate characters
        $generateCharactersCommand = $this->getApplication()->find('app:generate:characters');
        $arguments = [
            'command' => 'app:build:routine',
            'number'    => $totalCharacters
        ];

        $generateCharactersCommandInput = new ArrayInput($arguments);
        $generateCharactersCommand->run($generateCharactersCommandInput, $output);
    }

    /**
     * get routines from conf and from db
     *
     * @param $confRoutines
     * @return mixed
     */
    public function collectRoutines($confRoutines)
    {
        $routines = [];

        // get custom routines from conf
        $customRoutines = $confRoutines['custom'];

        foreach ($customRoutines as $name => $routine) {
            $routineName = (array_keys($routine))[0];
            $routineAction = $routine[$routineName];
            $routines[$routineName] = $routineAction;
        }

        // get default routines
        $routineGeneratorService = new RoutineGeneratorService($this->em);
        $defaultRoutines = $routineGeneratorService->createRoutines($confRoutines['default']['total']);

        foreach ($defaultRoutines as $name => $routine){
            $routines[$name] = $routine;
        }

        return $routines;
    }
}
