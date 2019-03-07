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
        $existingDefaultRoutines = $this->em->getRepository(Routine::class)->findDefaultRoutines();

        $routineName = 'default 15'; // select all default routine, add 1
        $routineActions = '{ {"'.$actionsList[0].'","18000","36000"} , {"'.$actionsList[1].'","36001","54000"}, {"'.$actionsList[2].'","54001","62000"}}';
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