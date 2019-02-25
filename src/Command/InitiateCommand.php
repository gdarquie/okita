<?php

namespace App\Command;

use App\Service\SQLService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InitiateCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:initiate';

    private $container;
    private $em;

    public function __construct($name = null, ContainerInterface $container, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->em = $em;
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
        $sqlService = new SQLService($this->em);
        $sqlService->initialize();

    }
}