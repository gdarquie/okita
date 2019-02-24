<?php

namespace App\Controller;

use App\SQL\Character\CharacterSQL;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SQLController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/sql/initalize", name="initialize")
     */
    public function initialize()
    {
        $this->initalizeContext();
        $this->initializeCharacter();
        $this->initializeAction();
        $this->initializeRoutine();

        dd('oki');
    }

    private function initalizeContext()
    {

    }

    /**
     *
     * Create all the characters function in SQL
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    private function initializeCharacter()
    {
        $this->createSQLFunction('character', 'getdefineDates');
        $this->createSQLFunction('character', 'getDefineGender');
        $this->createSQLFunction('character', 'getdefineSex');
        $this->createSQLFunction('character', 'generateCharacters');
        $this->createSQLFunction('character', 'generate_name');
        $this->createSQLFunction('character', 'getBirthdayByDecades');
        $result = true;

        return $result;
    }

    private function initializeAction()
    {

    }

    private function initializeRoutine()
    {

    }

    /**
     * @param $type
     * @param $queryName
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createSQLFunction($type, $queryName)
    {
        $connection = $this->em->getConnection();
        $query = $this->getSQLQuery($type, $queryName);

        $statement = $connection->executeQuery($query);
        $statement->fetchAll();
    }

    /**
     * @param $type
     * @return string
     */
    private function getClassName($type)
    {
        return 'App\SQL\\' . ucfirst($type) . '\\' . ucfirst($type) . 'SQL';
    }

    /**
     * @param $type
     * @param $query
     * @return mixed
     */
    private function getSQLQuery($type, $queryName)
    {
        $className = $this->getClassName($type);
        return (new $className())->$queryName();
    }

}
