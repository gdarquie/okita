<?php

namespace App\Repository;

use App\Entity\RoutineAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoutineAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutineAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutineAction[]    findAll()
 * @method RoutineAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoutineAction::class);
    }

    // /**
    //  * @return RoutineAction[] Returns an array of RoutineAction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoutineAction
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
