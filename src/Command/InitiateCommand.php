<?php

namespace App\Command;

use App\Service\SQLService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InitiateCommand extends AbstractSQLCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:initiate';

    public function __construct(ContainerInterface $container, EntityManagerInterface $em, $name = null)
    {
        parent::__construct($container, $em, $name,);
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Initialize Okita characters generator')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to set up the project for generating characters...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Initialisation begins',
            '============',
            '',
        ]);

        (new SQLService($this->em))->initialize();

        $output->writeln([
            'Success!',
            '============',
            '',
        ]);
    }
}