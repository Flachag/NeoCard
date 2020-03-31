<?php


namespace App\Controller\Admin;

use App\Entity\Transaction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class TransactionManagerController extends EasyAdminController
{
    protected function createNewEntity()
    {
        $result = parent::createNewEntity();

        $amount = $result->getAmount();
        $idReceiver = $result->getIdreceiver();


        $type = "Virement";
        $result->setType($type);

        $last = null;
        $manager = $this->getDoctrine()->getRepository(Transaction::class);
        $transactions = $manager->findAll();
        $count = count($transactions);
        if ($count == 0) {
            $hash = md5(ceil($amount) . '' . $idReceiver . $type);
        } else {
            $last = $transactions[$count - 1];
            $hash_last = $last->getHash();
            $hash = md5($hash_last . ceil($amount) . '' . $idReceiver . $type);
        }

        //il faut verifier que le hash de la précédente est bon
        if ($count == 1) { // cas ou il n'y a que la premiere transaction dans la base
            $expected_hash = md5(ceil($last->getAmount()) . $last->getIdissuer() . $last->getIdreceiver() . $last->getType());
        } else { //
            $last_prev_hash = $transactions[$count - 2]->getHash();
            $expected_hash = md5($last_prev_hash . ceil($last->getAmount()) . $last->getIdissuer() . $last->getIdreceiver() . $last->getType());
        }

        if ($expected_hash == $hash_last && $amount >= 0) {
            $result->setHash($hash);
            $result->setDate(new \DateTime());
        }


        return $result;
    }
}