<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function getDebit(Account $acc)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idissuer=:id'
        )->setParameter('id', $acc->getId());
        return $query->getResult()[0][1];
    }

    public function getTransactions(Account $account){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t 
                FROM App\Entity\Transaction t  
                WHERE t.idissuer=:id or t.idreceiver=:id ORDER BY t.id DESC'
        )->setParameter('id', $account->getId());
        return $query->getResult();
    }

    public function getCredit(Account $acc){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idreceiver=:id'
        )->setParameter('id', $acc->getId());
        return $query->getResult()[0][1];
    }

    public function getBalance(Account $acc): float{
        $debit = $this->getDebit($acc);
        $credit = $this->getCredit($acc);
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
