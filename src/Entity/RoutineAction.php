<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineActionRepository")
 */
class RoutineAction extends AbstractEntity
{
    /**
     * @ORM\Column(type="bigint", nullable=false)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="routinesActions")
     */
    private $routine;

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getRoutine()
    {
        return $this->routine;
    }

    /**
     * @param mixed $routine
     */
    public function setRoutine($routine): void
    {
        $this->routine = $routine;
    }

}
