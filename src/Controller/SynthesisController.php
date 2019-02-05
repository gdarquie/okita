<?php

namespace App\Controller;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SynthesisController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/synthesis", name="synthesis")
     */
    public function index()
    {
        return $this->render('synthesis.html.twig', [
            'charactersCount' => $this->countCharacter(),
            'maxAgeCharacter' => $this->maxAgeCharacter(),
        ]);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function countCharacter()
    {
        $query = $this->em->createQuery('SELECT COUNT(c.id) FROM '.Character::class.' c ');
        return $query->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function maxAgeCharacter()
    {
        $query = $this->em->createQuery('SELECT MAX((c.deathDate - c.birthDate)/(365*24*3600)) FROM '.Character::class.' c ');
        return $query->getSingleScalarResult();
    }
}
