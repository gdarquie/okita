<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Action::class);
        $this->em = $em;
    }

    /**
     * @param $id
     * @param $day
     * @return mixed
     */
    public function findByCharacter($characterId, $day)
    {
        $query = $this->em->createQuery(
            'SELECT a FROM '.Action::class.' a WHERE a.character = :characterId AND a.startAt >= :time  AND a.endAt <= :time+(3600*24)
        ORDER BY a.startAt ASC');
        $query->setParameter('characterId', $characterId);
        $query->setParameter('time', $day*3600*24);

        return $query->getResult();
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
