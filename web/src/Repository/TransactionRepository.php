<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
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

    public function effectuerTransaction($label,$amount,$idIssuer,$idReceiver,$type) {
        //si pas encore de transactions
        $transactions = $this->findAll();
        $hash_last = "";
        $last = new Transaction();
        $count = count($transactions);
        if($count == 0){
            $hash = md5(ceil($amount) . $idIssuer . $idReceiver . $type);
        }
        else{
            $last = $transactions[$count - 1];
            $hash_last = $last->getHash();
            $hash = md5($hash_last . ceil($amount) . $idIssuer . $idReceiver . $type);
        }

        //il faut verifier que le hash de la précédente est bon
        if($count == 1){ // cas ou il n'y a que la premiere transaction dans la base
            $expected_hash = md5(ceil($last->getAmount()) . $last->getIdissuer() . $last->getIdreceiver() . $last->getType());
        }
        else{ //
            $last_prev_hash = $transactions[$count - 2]->getHash();
            $expected_hash = md5($last_prev_hash . ceil($last->getAmount()) . $last->getIdissuer() . $last->getIdreceiver() . $last->getType());
        }

        //si les hash correspondent alors ok
        if($expected_hash == $hash_last){
            try {
                $transac = new Transaction();
                $transac->setLabel($label);
                $transac->setHash($hash);
                $transac->setAmount($amount);
                $transac->setIdissuer($idIssuer);
                $transac->setIdreceiver($idReceiver);
                $transac->setDate(new \DateTime());
                $transac->setType($type);

                $manager = $this->getEntityManager();
                $manager->persist($transac);
                $manager->flush();
            } catch (\Exception $e){}
        }
        else throw new \Exception("L'intégrité des transactions n'est plus bonne.");

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

    public function getBalanceAt($account, $date){
        $entityManager = $this->getEntityManager();
        $credit = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idreceiver=:id and t.date<=:date'
        )->setParameter('id', $account->getId()
        )->setParameter('date', $date);
        $credit = $credit->getResult()[0][1];

        $debit = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idissuer=:id and t.date<=:date'
        )->setParameter('id', $account->getId()
        )->setParameter('date', $date);
        $debit = $debit->getResult()[0][1];

        return $credit-$debit;
    }

    public function getBalanceMonth($account, $month){
        $entityManager = $this->getEntityManager();
        $credit = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idreceiver=:id and month(t.date)=:month'
        )->setParameter('id', $account->getId()
        )->setParameter('month', $month);
        $credit = $credit->getResult()[0][1];

        $debit = $entityManager->createQuery(
            'select sum(t.amount)
                  from App\Entity\Transaction t
                  where t.idissuer=:id and month(t.date)=:month'
        )->setParameter('id', $account->getId()
        )->setParameter('month', $month);
        $debit = $debit->getResult()[0][1];

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
