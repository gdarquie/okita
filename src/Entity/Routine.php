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
     * @ORM\ManyToMany(targetEntity="App\Entity\Character", mappedBy="routines")
     */
    private $characters;

    /**
     * the day where the routine happens
     *
     * todo: nullable = false
     *
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Work", inversedBy="habits")
     */
    private $work;

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

    /**
     * @return mixed
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param mixed $characters
     */
    public function setCharacters($characters): void
    {
        $this->characters = $characters;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day): void
    {
        $this->day = $day;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

        return $this;
    }

}
