<?php

namespace App\Repository;

use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Action::class);
    }

     /**
      * @return Action[] Returns an array of Action objects
      */
    public function findByPersonnage($characterId, $day)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.character = :characterId')
            ->andWhere('a.startAt = :day')
            ->setParameter('characterId', $characterId)
            ->setParameter('day', $day)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
}
