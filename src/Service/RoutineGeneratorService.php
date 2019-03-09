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
    public function createRoutine($actionsListRaw = [], $params = [])
    {
        if(!$actionsListRaw) {
            $actionsListRaw = $this->getActionsTypeList();
        }

        // generate name
        $routineName = $this->generateRoutineName();

        // select actions
        $actionsList = $this->selectAction($actionsListRaw);

        // manage time actions
        $sleepInfo = $this->manageSleepAction();

        // todo : manage actions for activityTime
        // 86400 = 24h * 60 min * 60 sec = a complete day
        $activityTime = 86400 - $sleepInfo['total'];

        // build actions
        $routineActions = [];
        $actionsListCount = count($actionsList);
        foreach ($actionsList as $key => $action) {

            if ($key === 0 && $actionsList[0] === 'sleep') {
                array_push($routineActions, [$actionsList[0], 0, $sleepInfo['exceedingTime']]);
            }

            else if ($key === ($actionsListCount-1) && $actionsList[($actionsListCount-1)] === 'sleep') {
            array_push($routineActions, [$action, $sleepInfo['begin'] ,"86400"]);
            }

            else {
                // todo : to change
                array_push($routineActions, [$action, "18000","36000"]);
            }
        }

        $routine = [ $routineName, $routineActions];

        return $routine;

        //todo : create many routines
        //todo : save the routines
    }

    /**
     * @param $actionsList
     * @return array
     */
    public function selectAction($actionsList)
    {
        $actions = ['sleep'];

        $nbActions = rand(1,3);

        for($i=0; $i < $nbActions; $i++) {
            $rand = (rand(1, count($actionsList))-1);
            array_push($actions, $actionsList[$rand]);
        }

        array_push($actions, 'sleep');

        return $actions;
    }

    public function manageSleepAction()
    {
        $sleepInfo = [];

        // how much times the character sleeps
        // 18000 = 5h max bonus
        $bonusSleepingTime = rand(1, 18000);
        // 108000 = 3h max minus
        $minusSleepingTime = rand(1, 10800);
        $sleepingTime = (25200 + $bonusSleepingTime - $minusSleepingTime);
        $sleepInfo['total'] = $sleepingTime;

        // by default the sleeping time is around 10PM = 79200
        $bonusSleepingBegining = rand(1, 18000);
        $minusSleepingBegining = rand(1, 10800);
        $beginSleepingTime = (79200 + $bonusSleepingBegining - $minusSleepingBegining);
        $sleepInfo['begin'] = $beginSleepingTime;

        if ($beginSleepingTime >= 86400) {
            $beginSleepingTime = 86400;
        }
        // compute the exceeding sleeping time
        $exceedingSleepingTime = $sleepingTime - (86400 - $beginSleepingTime);

        $sleepInfo['exceedingTime'] = $exceedingSleepingTime;

        return $sleepInfo;
    }

    /**
     * @return array
     */
    public function getActionsTypeList()
    {
        $actionsTypeList = ['sleep', 'walk', 'meet', 'work', 'dance', 'play', 'eat'];
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