<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Zend\Code\Scanner\Util;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getDebit(Utilisateur $user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'select sum(t.montant)
                  from App\Entity\Transaction t
                  where t.emetteur=:id'
        )->setParameter('id', $user->getIdutil());
        return $query->getResult()[0][1];
    }

    public function getCredit(Utilisateur $user){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'select sum(t.montant)
                  from App\Entity\Transaction t
                  where t.recepteur=:id'
        )->setParameter('id', $user->getIdutil());

        return $query->getResult()[0][1];
    }

    public function getSolde(Utilisateur $user): float{
        $debit = $this->getDebit($user);
        $credit = $this->getCredit($user);
        return $credit-$debit;
    }
    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
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
    public function findOneBySomeField($value): ?Transaction
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
