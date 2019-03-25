<?php

namespace App\Service;

use App\Entity\Routine;
use Doctrine\ORM\EntityManagerInterface;

class RoutineGeneratorService
{
    /**
     * @var EntityManagerInterface
     */
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
    public function createRoutines($nbRoutines, $list = []) :array
    {
        $routines = [];

        for($i = 0; $i < $nbRoutines; $i++) {
            $routine = $this->createRoutine($list);
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

        // generate name for the routine
        $routineName = $this->generateRoutineName();

        // select actions connected to the routine
        $actionsList = $this->getActions($actionsListRaw);

        // prepare sleep operation
        $sleepInfo = $this->manageSleepAction();

        // prepare activities action
        // todo : manage actions for activityTime
        // 86400 = 24h * 60 min * 60 sec = a complete day
        $activityTime = 86400 - $sleepInfo['total'];
        // durée des actions... supprimer des actions?

        // build actions
        $routineActionsRaw = $this->buildSeries($actionsList, $sleepInfo);

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
    public function getActions($actionsList)
    {
        //todo : change this action
        $actions = ['dormir'];

        $nbActions = rand(1,3);

        for($i=0; $i < $nbActions; $i++) {
            $rand = (rand(1, count($actionsList))-1);
            array_push($actions, $actionsList[$rand]);
        }

        array_push($actions, 'dormir');

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
        // todo: get action list from conf
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
     * Generate a series of actions with the ones contained in the action list
     *
     * @param $actionsList
     * @param $sleepInfo
     * @return array
     */
    public function buildSeries(Array $actionsList, Array $sleepInfo):array
    {
        $routineActions = [];
        $actionsListCount = count($actionsList);
        $activitiesListCount = ($actionsListCount-2);

        $activityTime = 86400 - $sleepInfo['total'];
        $actionTime = intdiv($activityTime,$activitiesListCount);

        foreach ($actionsList as $key => $action) {

            // add one action to the routine
            array_push($routineActions, $this->returnAction($key, $action, $actionsList, $activitiesListCount, $sleepInfo, $actionTime, $routineActions));
        }

        return $routineActions;
    }


    private function returnAction($key, $action, $actionsList, $actionsListCount, $sleepInfo, $actionTime, $routineActions): array
    {

        if ($key === 0 && $actionsList[0] === 'sleep') {
            $result = [$actionsList[0], 0, $sleepInfo['exceedingTime']];
        }

        else if ($key === ($actionsListCount-1) && $actionsList[($actionsListCount-1)] === 'sleep') {
            $result =  [$action, $sleepInfo['begin'] ,"86400"];
        }

        else {
            //we had one to the end time of the last routine
            $startRoutine = (end($routineActions)[2]+1);
            $endRoutine = ($startRoutine+$actionTime);

            $result = [$action, $startRoutine, $endRoutine];
        }

        return $result;
    }

    /**
     * convert action format into {"action", "start", "end"}
     *
     * @param array $listActionsRaw
     * @return string
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