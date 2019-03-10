<?php

namespace App\Service;

use App\Entity\Routine;
use Doctrine\ORM\EntityManagerInterface;

class RoutineGeneratorService
{
    private $em;
    private $lastRoutineId;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $nbRoutines
     * @return array
     */
    public function createRoutines($nbRoutines) :array
    {
        $routines = [];

        for($i = 0; $i < $nbRoutines; $i++) {
            $routine = $this->createRoutine();
            $routineName = (array_keys($routine))[0];
            $routineAction = $routine[$routineName];
            $routines[$routineName] = $routineAction;
        }

        return $routines;
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

        // prepare sleep operation
        $sleepInfo = $this->manageSleepAction();

        // prepare activities action
        // todo : manage actions for activityTime
        // 86400 = 24h * 60 min * 60 sec = a complete day
        $activityTime = 86400 - $sleepInfo['total'];
        // durée des actions... supprimer des actions?

        // build actions
        $routineActionsRaw = $this->buildActions($actionsList, $sleepInfo);

        // convert actions into json
        $routineActions = $this->transformActions($routineActionsRaw);

        $routine[$routineName] =  $routineActions;

        //todo : save the routine ??? Elsewhere?

        return $routine;
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
        (!$this->lastRoutineId) ? $this->lastRoutineId = $this->em->getRepository(Routine::class)->findLastRoutineId()+1 : $this->lastRoutineId++;
        return 'default'.$this->lastRoutineId;
    }

    /**
     * @param $actionsList
     * @param $sleepInfo
     * @return array
     */
    public function buildActions(Array $actionsList, Array $sleepInfo):array
    {
        $routineActions = [];
        $actionsListCount = count($actionsList);
        $activitiesListCount = ($actionsListCount-2);

        $activityTime = 86400 - $sleepInfo['total'];
        $actionTime = intdiv($activityTime,$activitiesListCount);

        foreach ($actionsList as $key => $action) {

            if ($key === 0 && $actionsList[0] === 'sleep') {
                array_push($routineActions, [$actionsList[0], 0, $sleepInfo['exceedingTime']]);
            }

            else if ($key === ($actionsListCount-1) && $actionsList[($actionsListCount-1)] === 'sleep') {
                array_push($routineActions, [$action, $sleepInfo['begin'] ,"86400"]);
            }

            else {
                //we had one to the end time of the last routine
                $startRoutine = (end($routineActions)[2]+1);
                $endRoutine = ($startRoutine+$actionTime);

                array_push($routineActions, [$action, $startRoutine, $endRoutine]);
            }
        }

        return $routineActions;
    }

    /**
     * convert action format into {"action", "start", "end"}
     *
     * @param array $listActionsRaw
     * @return array
     */
    public function transformActions(Array $listActionsRaw): string
    {
        $actions ='';
        $counter = 0;
        $max = count($listActionsRaw);

        foreach ($listActionsRaw as $rawAction) {
            $counter++;
            ($counter === 1)? $actions = '{' : '';

            $actions = $actions.'{"'.$rawAction[0].'","'.$rawAction[1].'","'.$rawAction[2].'"}';
            ($counter < $max)? $actions = $actions.',' : $actions = $actions.'}';
        }

        return $actions;
    }

}