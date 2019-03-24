<?php

namespace App\Command\Create;

use App\Command\AbstractSQLCommand;
use App\Service\RoutineGeneratorService;
use App\Service\SQLService;
use Symfony\Component\Console\Helper\ProgressBar;
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
            ->setDescription('Generate a new project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Configuration generation begins',
            '============',
            '',
        ]);

        // Get project file and parse config file for setting vars
        $value = Yaml::parseFile($this->getProjectFile());
        $totalCharacters = $value['characters']['total'];
        $routines = $this->collectRoutines($value['routines']);

        $io->success('Configuration pathes and config setted!');

        // Initialization, install SQL functions
        (new SQLService($this->em))->initialize();

        $io->success('Initialization succeeds!');

        // Create Routines : launch command for generating default and custom routines
        $io->success($this->createRoutines($routines, $output));

        // Launch command for characters generation : launch command
        $generateCharactersCommand = $this->getApplication()->find('app:generate:characters');
        $arguments = [
            'command' => 'app:build:routine',
            'number'    => $totalCharacters
        ];
        $generateCharactersCommandInput = new ArrayInput($arguments);
        $generateCharactersCommand->run($generateCharactersCommandInput, $output);

        $io->success('Character generation succeeds!');
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
        $defaultRoutines = $routineGeneratorService->createRoutines($confRoutines['default']['total'], $confRoutines['default']['actions']);

        foreach ($defaultRoutines as $name => $routine){
            $routines[$name] = $routine;
        }

        return $routines;
    }

    /**
     * @param String $project
     * @return string
     */
    private function getProjectFile(): string
    {
        $rootPath = $this->container->get('kernel')->getProjectDir();
        $projectPath = $rootPath.'/src/Domain/Configuration';

        return $projectPath.'/project.yaml';
    }

    private function createRoutines($routines, $output)
    {
        $buildRoutinesCommand = $this->getApplication()->find('app:build:routine');

        $progressBar = new ProgressBar($output, count($routines));
        $progressBar->start();
        foreach ($routines as $key => $value) {

            $arguments = [
                'command' => 'app:build:routine',
                'name'    => $key,
                'content'  => $value,
            ];

            $buildRoutinesCommandInput = new ArrayInput($arguments);
            $buildRoutinesCommand->run($buildRoutinesCommandInput, $output);
            $progressBar->advance();
        }

        return 'Routine generation succeeds!';
    }
}
