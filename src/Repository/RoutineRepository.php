<?php

namespace App\Repository;

use App\Entity\Routine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Routine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Routine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Routine[]    findAll()
 * @method Routine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Routine::class);
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function findDefaultRoutines()
    {
        $query = $this->em->createQuery(
            'SELECT r FROM '.Routine::class.' r WHERE r.name LIKE :default ORDER BY r.updatedAt DESC');
        $query->setParameter('default', '%default%');

        return $query->getResult();
    }

    public function findLastRoutineId()
    {
        $query = $this->em->createQuery(
            'SELECT r.id FROM '.Routine::class.' r WHERE r.name LIKE :default ORDER BY r.id DESC');
        $query->setParameter('default', '%default%');
        $query->setMaxResults(1);

        return $query->getOneOrNullResult();
    }
}
