<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class GenerateActionsCommand extends AbstractSQLCommand
{
    protected static $defaultName = 'app:gen:act';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootPath = $this->container->get('kernel')->getProjectDir();
        $projectPath = $rootPath.'/src/Domain/Project';
        $projectFile = $projectPath.'/la-degradation.yaml';

        $value = Yaml::parseFile($projectFile);

        $totalCharacters = $value['characters']['total'];

        $command = $this->getApplication()->find('demo:greet');

        $arguments = [
            'command' => 'demo:greet',
            'name'    => 'Fabien',
            '--yell'  => true,
        ];

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);

        dd($value['characters']['total']);

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
