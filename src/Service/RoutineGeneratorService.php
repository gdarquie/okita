<?php

namespace App\Service;

use App\Entity\Routine;
use Doctrine\ORM\EntityManagerInterface;

class RoutineGeneratorService
{
    private $em;
    private $connection;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $this->em->getConnection();;
    }

    /**
     * @return array
     */
    public function createRoutine($actionsList = [])
    {
        if(!$actionsList) {
            $actionsList = $this->getActionsTypeList();
        }

        //generateName
        $routineName = $this->generateRoutineName();

        //genereteActions
        $routineActions = [ 0 => [ $actionsList[0], "18000","36000"], 1 => [$actionsList[1], "36001","54000"], 2 => [$actionsList[2],"54001","62000"]];
        $routine = [ $routineName, $routineActions];

        return $routine;
    }

    /**
     * @return array
     */
    public function getActionsTypeList()
    {
        $actionsTypeList = ['dormir', 'manger', 'marcher', 'rencontrer', 'travailler', 'rencontrer'];
        return $actionsTypeList;

    }

    /**
     * @return string
     */
    public function generateRoutineName()
    {
        return 'default-'.($this->em->getRepository(Routine::class)->findLastRoutineId()+1);
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