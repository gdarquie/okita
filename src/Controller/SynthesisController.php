<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Character;
use App\Entity\Habit;
use App\Entity\Setting;
use App\Service\RoutineGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SynthesisController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SynthesisController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="synthesis")
     */
    public function index()
    {
//        $routineGeneratorSevice = new RoutineGeneratorService($this->em);
//        $routine = $routineGeneratorSevice->createRoutine();
//
//        dd($routine);


        $statsCharacter = $this->getStatsCharacters();
        $statsAction = $this->getStatsAction();
        $statsHabit = $this->getStatsHabit();

        return $this->render('synthesis.html.twig', [
            'character' => $this->getRandomCharacter(rand($statsCharacter['min_id'],$statsCharacter['max_id'])),
            'statsCharacter' => $statsCharacter,
            'statsAction' => $statsAction,
            'statsHabit' => $statsHabit,
            'ratioSex' => $this->getRatioSex(),
            'ageByDecade' => $this->getAgeByDecade(),
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
        $query = $this->em->createQuery('SELECT COUNT(c.id) as total, MAX((c.deathDate - c.birthDate)/(365*24*3600)) as max_age, MIN((c.deathDate - c.birthDate)/(365*24*3600)) as min_age, MAX(c.id) as max_id, MIN(c.id) as min_id FROM '.Character::class.' c ');
        $stats = $query->getSingleResult();
        
        return $stats;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getStatsAction()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(a.id) as total FROM '.Action::class.' a'
        );
        $stats = $query->getSingleResult();

        return $stats;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getStatsHabit()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(h.id) as total FROM '.Habit::class.' h'
        );
        $stats = $query->getSingleResult();

        return $stats;
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAgeByDecade()
    {
        $sql = "SELECT array_to_json(get_birthdate_by_decades(-100, 0))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $inputResults = json_decode($results[0]['array_to_json']);
        $outputResults = [];

        foreach ($inputResults as $result) {
            array_push($outputResults, json_decode($result, true));
        }

        return $outputResults;
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
