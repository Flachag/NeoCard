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
     * @api {post} / Ouvre une session avec un rôle qui a accès à l'API.
     * @apiName login
     * @apiGroup Login
     *
     * @apiParam {String} _password Mot de passe du compte.
     * @apiParam {String} _username Identifiant du compte du compte.
     */

    /**
     * @api {get} /deconnexion Ferme la session.
     * @apiName deconnexion
     * @apiGroup Login
     *
     */

    /**
     * @Route("/api/checkIP/{ip}", name="checkip_api")
     * @api {get} /api/checkIP/{ip} Retourne le numéro de comtpe associé au TPE.
     * @apiName checkIP
     * @apiGroup Serveur API
     *
     * @apiParam {int} ip ip à vérifier.
     *
     * @apiSuccess {String} idAccount le comtpe associé à l'IP.
     * @apiError (Error 404 not found) {int} idAccount -1 si l'ip est associée à aucun compte.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "idAccount": "d4ff7e629c3e666e09c7",
     *     }
     *
     */
    public function checkIP($ip){

        $repo = $this->getDoctrine()->getRepository(Terminal::class);
        $terminal = $repo->findBy(['ip' => $ip]);
        if (isset($terminal[0])) {
            $idAccount = $terminal[0]->getIdaccount();
            return new Response('{"idAccount":"'. $idAccount .'" }', 200,['idAccount' => $idAccount]);
        }
        else return new Response('{"idAccount":'. -1 .' }', 404,['idAccount' => -1]);

    }

    /**
     * @Route("/api/soldeCompte/{accountId}", name="soldeCompte_api")
     * @api {get} /api/soldeCompte/{accountId} Retourne le solde du compte associé au numéro.
     * @apiName soldeCompte
     * @apiGroup Serveur API
     *
     * @apiParam {int} accountId id du compte dont l'on veut le solde.
     *
     * @apiSuccess {double} solde le solde du compte associé à idAccount.
     * @apiError (Error 404 not found) {int} solde -1 si idAccount est associé à aucun compte.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "solde": 78.2,
     *     }
     *
     */
    public function soldeCompte($accountId){
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(['id' => $accountId]);
        if(isset($account[0])) {
            $solde = $repo->getBalance($account[0]);
            return new Response('{"solde":"'. $solde .'" }', 200,['solde' => $solde]);
        }
        else {
            $solde = -1;
            return new Response('{"solde":"'. $solde .'" }', 404,['solde' => $solde]);
        }

    }

    /**
     * @Route("/api/pay/{idReceiver}/{cardUID}/{amount}", name="pay_api")
     * @api {get} api/pay/{idReceiver}/{cardUID}/{amount} Fait un paiement.
     * @apiName pay
     * @apiGroup Serveur API
     *
     * @apiParam {int} idReceiver id du compte qui va recevoir le paiement.
     * @apiParam {String} cardUID UID de la carte qi effectue le paiement.
     * @apiParam {double} amount montant du paiement.
     *
     * @apiSuccess {String} status success si le paiement est effectué.
     * @apiError (Error 403) {String} status error si le paiement ne peut pas être fait.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": "success",
     *     }
     */
    public function pay($idReceiver, $cardUID, $amount){
        $idIssuer = $this->getDoctrine()->getRepository(Card::class)->getUserIdByCardUid($cardUID);

        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(['id' => $idIssuer]);
        if(isset($account[0]))
            $solde = $repo->getBalance($account[0]);
        else $solde = -1;


        if (isset($idIssuer) && $idIssuer != null && $amount > 0 && $amount <= $solde){
            try {
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
            } catch (\Exception $e){
                return new Response('{"status":"'. 'error' .'" }',403);
            }


            return new Response('{"status":"'. 'success' .'" }',200);
        }
        else return new Response('{"status":"'. 'error' .'" }',403);
    }






}