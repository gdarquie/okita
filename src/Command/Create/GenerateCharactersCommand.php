<?php

namespace App\Command\Create;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateCharactersCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate:characters';

    private $container;
    private $em;
    private $connection;

    public function __construct($name = null, ContainerInterface $container, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->em = $em;
        $this->connection = $this->em->getConnection();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate characters')
            ->setHelp('This command allows you to generate characters...')
            ->addArgument('number', InputArgument::OPTIONAL, 'The number of characters to create', 10)

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Characters generator',
            '============',
            '',
        ]);

        $number = $input->getArgument('number');

        $statement = $this->connection->executeQuery('SELECT generate_characters('.$number.')');
        $statement->fetchAll();

        $output->writeln($number.' characters successfully generated!');
    }
}