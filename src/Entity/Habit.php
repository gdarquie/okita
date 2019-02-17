<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HabitRepository")
 */
class Habit extends AbstractEntity
{
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $start;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="routinesActions")
     */
    private $routine;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start): void
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end): void
    {
        $this->end = $end;
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
