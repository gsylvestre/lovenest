<?php

namespace App\Repository;

use App\Entity\SearchCriterias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchCriterias|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchCriterias|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchCriterias[]    findAll()
 * @method SearchCriterias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchCriteriasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchCriterias::class);
    }

    // /**
    //  * @return SearchCriterias[] Returns an array of SearchCriterias objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchCriterias
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
