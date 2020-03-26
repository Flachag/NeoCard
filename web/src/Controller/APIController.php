<?php


namespace App\Controller;

use App\Entity\Account;
use App\Entity\Card;
use App\Entity\Terminal;
use App\Entity\Transaction;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIController extends AbstractController{

    /**
     * @Route("/testapi/{bleb}", name="test_api")
     */
    public function testapi($bleb){
        if($bleb > 10) return new Response($bleb,404);
        else return new Response($bleb, 200);

    }

    /**
     * @Route("/api/checkIP/{ip}", name="checkip_api")
     */
    public function checkIP($ip){

        $repo = $this->getDoctrine()->getRepository(Terminal::class);
        $terminal = $repo->findBy(['ip' => $ip]);
        if (isset($terminal[0])) {
            $idAccount = $terminal[0]->getIdaccount();
            return new Response('Succès.', 200,['idAccount' => $idAccount]);
        }
        else return new Response('Erreur: terminal inconnu.', 404,['idAccount' => -1]);

    }

    /**
     * @Route("/api/soldeCompte/{accountId}", name="soldeCompte_api")
     */
    public function soldeCompte($accountId){
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(['id' => $accountId]);
        if(isset($account[0]))
            $solde = $repo->getBalance($account[0]);
        else $solde = -1;

        return new Response($solde, 200,['solde' => $solde]);

    }

    /**
     * @Route("/api/pay/{idReceiver}/{cardUID}/{amount}", name="pay_api")
     */
    public function pay($idReceiver, $cardUID, $amount){
        $idIssuer = $this->getDoctrine()->getRepository(Card::class)->getUserIdByCardUid($cardUID);

        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(['id' => $idIssuer]);
        if(isset($account[0]))
            $solde = $repo->getBalance($account[0]);
        else $solde = -1;


        if (isset($idIssuer) && $idIssuer != null && $amount > 0 && $amount <= $solde){
            $transac = new Transaction();
            $transac->setLabel("Paiement TPE");
            $transac->setAmount($amount);
            $transac->setIdissuer($idIssuer);
            $transac->setIdreceiver($idReceiver);
            $transac->setDate(new \DateTime());
            $transac->setType("Paiement TPE");
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($transac);
            $manager->flush();

            return new Response('Transaction effectuée.',200);
        }
        else return new Response('Transaction impossible',403);
    }






}