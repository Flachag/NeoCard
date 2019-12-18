<?php

namespace App\Repository;

use App\Entity\Tpe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tpe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tpe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tpe[]    findAll()
 * @method Tpe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TpeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tpe::class);
    }

    // /**
    //  * @return Tpe[] Returns an array of Tpe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tpe
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
