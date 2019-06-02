<?php

namespace App\Command\Create;

use App\Service\Generator\GeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Characters generator',
            '============',
            '',
        ]);

        $characterNumber = $input->getArgument('number');

        // max number of character generation is 100K
        if($characterNumber > 100000)
        {
            $characterNumber = 100000;
            $output->writeln('Max character number is 100 000!');
        }

        // we upgrade memory limit for big import
        if($characterNumber > 10000)
        {
            ini_set('memory_limit', '512M');
        }

        $generator = new GeneratorService();
        $progressBar = new ProgressBar($output, $characterNumber);
        $progressBar->start();

        // save all bases characters
        for($currentLine = 0; $currentLine < $characterNumber; $currentLine++)
        {
            // generate character
            $character = $generator->generateBaseCharacter();

            // save character
            $this->saveCharacter($character, $progressBar, $characterNumber, $currentLine);
        }

        $progressBar->finish();

//        $statement = $this->connection->executeQuery('SELECT generate_characters('.$number.')');
//        $statement->fetchAll();

        $output->writeln(' '.$characterNumber.' characters successfully generated!');
    }

    /**
     * @param $character
     * @param $progressBar
     * @param $characterNumber
     */
    protected function saveCharacter($character, $progressBar, $characterNumber, $currentLine)
    {
        $this->em->persist($character);

        // we flush and clear every 1000 turns  or if it is the last turn
        if($characterNumber % 1000  == 0 || ($currentLine+1) == $characterNumber)
        {
            $this->em->flush();
            $this->em->clear();
        }

        $progressBar->advance();
    }

}
