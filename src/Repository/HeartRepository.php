<?php

namespace App\Repository;

use App\Entity\Heart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Heart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Heart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Heart[]    findAll()
 * @method Heart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Heart::class);
    }

    public function findExistingHeartBetweenTwoUser(\App\Entity\User $user1, \App\Entity\User $user2)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->andWhere('h.sentTo = :user1 OR h.initiatedBy = :user1')->setParameter(':user1', $user1);
        $qb->andWhere('h.sentTo = :user2 OR h.initiatedBy = :user2')->setParameter(':user2', $user2);
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }

    // /**
    //  * @return Heart[] Returns an array of Heart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Heart
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
