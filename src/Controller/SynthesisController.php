<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Character;
use App\Entity\Habit;
use App\Entity\Routine;
use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

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
        $statsCharacter = $this->getDoctrine()->getRepository(Character::class)->findStatsCharacters();
        $statsRoutine = $this->getStatsRoutine();
        $statsParams = $this->getStatsParams();

        return $this->render('synthesis.html.twig', [
            'character' => $this->getDoctrine()->getRepository(Character::class)->findOneById(rand($statsCharacter['min_id'],$statsCharacter['max_id'])),
            'statsCharacter' => $statsCharacter,
            'statsAction' => $this->getDoctrine()->getRepository(Action::class)->countActions(),
            'statsHabit' => $this->getDoctrine()->getRepository(Habit::class)->countHabits(),
            'ratioSex' => $this->getDoctrine()->getRepository(Character::class)->countBySex(),
            'ageByDecade' => $this->getAgeByDecade(),
            'statsRoutine' => $statsRoutine,
            'statsParams' => $statsParams
        ]);
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAgeByDecade() :array
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
     */
    public function getStatsFiction(): array
    {
        $query = $this->em->createQuery('SELECT s FROM '.Setting::class.' s WHERE s.key = :key');
        $query->setParameter('key', '');
        $stats['fiction_begin'] = $query->getResult();

        $query = $this->em->createQuery('SELECT s FROM '.Setting::class.' s WHERE s.key = :key');
        $query->setParameter('key', '');
        $stats['fiction_end'] = $query->getResult();

        return $stats;
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatsRoutine(): array
    {
        // Number of routines
        $stats['total'] = $this->getDoctrine()->getRepository(Routine::class)->countRoutines();

        // Number of characters linked to habits
        $query = $this->em->createQuery(
            'SELECT COUNT(c) FROM '.Character::class.' c JOIN c.routines r'
        );
        $stats['total_linked'] = $query->getSingleScalarResult();

        // Habits connected with the more characters
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) as nb, r.name  FROM '.Character::class.' c JOIN c.routines r GROUP BY r.id ORDER BY nb DESC'
        );
        $query->setMaxResults(1);
        $stats['most_used'] = $query->getSingleResult();

        // Habits connected with the less characters
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) as nb, r.name  FROM '.Character::class.' c JOIN c.routines r GROUP BY r.id ORDER BY nb ASC'
        );
        $query->setMaxResults(1);
        $stats['less_used'] = $query->getSingleResult();

        return $stats;
    }

    /**
     * @return array
     */
    public function getStatsParams($project = 'la-degradation'): array
    {
        $rootPath = $this->getParameter('kernel.project_dir');
        $projectPath = $rootPath.'/src/Domain/Configuration';
        $projectFile = $projectPath.'/'.$project.'.yaml';

        $value = Yaml::parseFile($projectFile);
        $stats = $value['fiction'];

        return $stats;
    }
}
