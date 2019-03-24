<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Character;
use App\Entity\Habit;
use App\Entity\Routine;
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
        $statsCharacter = $this->getCharacterRepository()->findStatsCharacters();

        return $this->render('synthesis.html.twig', [
            'character' => $this->getCharacterRepository()->findOneById(rand($statsCharacter['min_id'],$statsCharacter['max_id'])),
            'statsCharacter' => $statsCharacter,
            'statsAction' => $this->getDoctrine()->getRepository(Action::class)->countActions(),
            'statsHabit' => $this->getDoctrine()->getRepository(Habit::class)->countHabits(),
            'ratioSex' => $this->getCharacterRepository()->countBySex(),
            'ageByDecade' => $this->getAgeByDecade(),
            'statsRoutine' => $this->getStatsRoutine(),
            'statsParams' => $this->getStatsParams()
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
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatsRoutine(): array
    {
        // Number of routines
        $stats['total'] = $this->getDoctrine()->getRepository(Routine::class)->countRoutines();

        // Number of characters linked to habits
        $stats['total_linked'] = $this->getCharacterRepository()->countCharactersRoutines();

        // Habits connected with the more characters
        $stats['most_used'] = $this->getCharacterRepository()->findMostUsedRoutine();

        // Habits connected with the less characters
        $stats['less_used'] = $this->getCharacterRepository()->findLessUsedRoutine();

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

    /**
     * @return \App\Repository\CharacterRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getCharacterRepository()
    {
        return $this->getDoctrine()->getRepository(Character::class);
    }
}
