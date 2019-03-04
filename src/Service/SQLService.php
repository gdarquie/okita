<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SQLService
{
    private $em;
    private $connection;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $this->em->getConnection();;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function initialize():bool
    {
        $this->initalizeHelper();
        $this->initializeCharacter();
        $this->initializeAction();
        $this->initializeRoutine();

        return true;
    }

    private function initalizeHelper():bool
    {
        $this->createSQLFunction('helper', array(
            'getRandomBetween',
        ));

        return true;
    }

    /**
     *
     * Create all the characters function in SQL
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    private function initializeCharacter():bool
    {
        $this->createSQLFunction('character', array(
            'getdefineDates',
            'getDefineGender',
            'getdefineSex',
            'generateCharacters',
            'generate_name',
            'getBirthdayByDecades'
        ));

        return true;
    }

    private function initializeAction():bool
    {
        $this->createSQLFunction('action', array(
            'getInsertAction',
            'getGenerateActionForDay',
            'generate_actions_for_day_and_character',
            'generateActionFromDayToDay'
        ));

        return true;
    }

    private function initializeRoutine():bool
    {
        $this->createSQLFunction('routine', array(
            'getBuildRoutine',
            'getCreateHabit'
        ));

        return true;
    }

    /**
     * @param $type
     * @param array $queryNames
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createSQLFunction($type, Array $queryNames = [] )
    {
        foreach ($queryNames as $queryName) {
            $query = $this->getSQLQuery($type, $queryName);
            $statement = $this->connection->executeQuery($query);
            $statement->fetchAll();
        }

    }

    /**
     * @param $type
     * @return string
     */
    private function getClassName(String $type):string
    {
        return 'App\Component\SQL\\' . ucfirst($type) . 'SQL';
    }

    /**
     * @param String $type
     * @param String $queryName
     * @return mixed
     */
    private function getSQLQuery(String $type, String $queryName)
    {
        $className = $this->getClassName($type);
        return (new $className())->$queryName();
    }

}