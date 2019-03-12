<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 */
class Character extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="bigint", nullable=false)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="bigint", nullable=false)
     */
    private $deathDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Routine", inversedBy="characters")
     */
    private $routines;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * @param mixed $deathDate
     */
    public function setDeathDate($deathDate): void
    {
        $this->deathDate = $deathDate;
    }

    /**
     * @return mixed
     */
    public function getRoutines()
    {
        return $this->routines;
    }

    /**
     * @param mixed $routines
     */
    public function setRoutines($routines): void
    {
        $this->routines = $routines;
    }

    /**
     * @return string
     */
    public function getPronoun(): string
    {
        ($this->getSex() === 'F') ? $pronoun = 'elle' : $pronoun = 'il';

        return $pronoun;
    }
}
