<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Character::class);
        $this->em = $em;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findStatsCharacters()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(c.id) as total, MAX((c.deathDate - c.birthDate)/(365*24*3600)) as max_age, MIN((c.deathDate - c.birthDate)/(365*24*3600)) as min_age, MAX(c.id) as max_id, MIN(c.id) as min_id FROM '.Character::class.' c');

        return $query->getSingleResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBySex()
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countCharacters()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(c) FROM '.Character::class.' c');

        return $query->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countCharactersRoutines()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(c) FROM '.Character::class.' c JOIN c.routines r');

        return $query->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findMostUsedRoutine()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) as nb, r.name  FROM '.Character::class.' c JOIN c.routines r GROUP BY r.id ORDER BY nb DESC'
        );
        $query->setMaxResults(1);

        return $query->getSingleResult();
    }

    public function findLessUsedRoutine()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) as nb, r.name  FROM '.Character::class.' c JOIN c.routines r GROUP BY r.id ORDER BY nb ASC'
        );
        $query->setMaxResults(1);

        return $query->getSingleResult();
    }


}
