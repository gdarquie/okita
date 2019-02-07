<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Setting;
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
     * @Route("/", name="synthesis")
     */
    public function index()
    {
        $stats = $this->getStatsCharacters();

        return $this->render('synthesis.html.twig', [
            'character' => $this->getRandomCharacter(rand($stats['min_id'],$stats['max_id'])),
            'statsCharacter' => $stats,
            'ratioSex' => $this->getRatioSex()
        ]);
    }

    /**
     * @param $randomId
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getRandomCharacter($randomId)
    {
        $query = $this->em->createQuery('SELECT c FROM '.Character::class.' c WHERE c.id = :id');
        $query->setParameter('id', $randomId);
        $character = $query->getSingleResult();

        return $character;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getStatsCharacters()
    {
        $query = $this->em->createQuery('SELECT COUNT(c.id) as total,MAX((c.deathDate - c.birthDate)/(365*24*3600)) as max_age, MAX(c.id) as max_id, MIN(c.id) as min_id FROM '.Character::class.' c ');
        $stats = $query->getSingleResult();
        
        return $stats;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRatioSex()
    {
        $query = $this->em->createQuery('SELECT COUNT(c.id) as total FROM '.Character::class.' c WHERE c.sex = :sex GROUP BY c.sex');
        $query->setParameter('sex', 'F');
        $stats['female'] = $query->getSingleScalarResult();

        $query = $this->em->createQuery('SELECT COUNT(c.id) as total FROM '.Character::class.' c WHERE c.sex = :sex GROUP BY c.sex');
        $query->setParameter('sex', 'M');
        $stats['male'] = $query->getSingleScalarResult();

        return $stats;
    }

    /**
     * @return mixed
     */
    public function getStatsFiction()
    {
        $query = $this->em->createQuery('SELECT s FROM '.Setting::class.' s WHERE s.key = :key');
        $query->setParameter('key', '');
        $stats['fiction_begin'] = $query->getResult();

        $query = $this->em->createQuery('SELECT s FROM '.Setting::class.' s WHERE s.key = :key');
        $query->setParameter('key', '');
        $stats['fiction_end'] = $query->getResult();

        return $stats;
    }
}
