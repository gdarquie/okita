<?php

namespace App\Component\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class generateCharactersCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:gen:char';

    private $container;

    public function __construct($name = null, ContainerInterface $container)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Generate characters')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to generate characters...')
            ->addArgument('number', InputArgument::OPTIONAL, 'The number of characters to create', 10)

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine');
        $connection = $em->getConnection();

        $output->writeln([
            'Characters generator',
            '============',
            '',
        ]);

        $number = $input->getArgument('number');

        $statement = $connection->executeQuery('SELECT generate_characters('.$number.')');
        $statement->fetchAll();

        $output->writeln($number.' characters successfully generated!');
    }
}