<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineRepository")
 */
class Routine extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Habit", mappedBy="routine")
     */
    private $routinesActions;

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
    public function getRoutinesActions()
    {
        return $this->routinesActions;
    }

    /**
     * @param mixed $routinesActions
     */
    public function setRoutinesActions($routinesActions): void
    {
        $this->routinesActions = $routinesActions;
    }


}
