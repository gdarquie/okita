<?php

namespace App\Command;

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
        $routines = $value['routines'];
        $io->success('Project pathes and config setted!');


        // Launch commands

        // Initialization
        $output->writeln([
            'Initialization is launched',
            '============',
            '',
        ]);
        (new SQLService($this->em))->initialize();
        $io->success('Initialization succeeds!');


        // Create Routines
        $buildRoutinesCommand = $this->getApplication()->find('app:build-routines');

        foreach ($routines as $routine) {
            $key = (array_keys($routine))[0];
            $value = $routine[$key];

            $arguments = [
                'command' => 'app:build-routines',
                'name'    => $key,
                'content'  => $value,
            ];

            $buildRoutinesCommandInput = new ArrayInput($arguments);
            $buildRoutinesCommand->run($buildRoutinesCommandInput, $output);
        }

        //generate characters
        $generateCharactersCommand = $this->getApplication()->find('app:generate:characters');
        $arguments = [
            'command' => 'app:build-routines',
            'number'    => $totalCharacters
        ];

        $generateCharactersCommandInput = new ArrayInput($arguments);
        $generateCharactersCommand->run($generateCharactersCommandInput, $output);
    }
}
