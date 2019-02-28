<?php

namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractSQLCommand extends Command
{
    protected $container;
    protected $em;
    protected $connection;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em, $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->em = $em;
        $this->connection = $this->em->getConnection();
    }
}