<?php


namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DashboardController extends AbstractController{

    /**
     * @var Environment
     */
    private $user;
    private $transactions;
    private $account;


    public function __construct(UtilisateurRepository $users, TransactionRepository $transac, CompteRepository $acc){
        $this->user = $users;
        $this->transactions = $transac;
        $this->account = $acc;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(){
        $user = $this->get('session')->get('user')[0];
        $accounts = $this->account->findBy(['idutil' => $user->getIdUtil()]);
        $solde = null;
        if($user != null) {
            $solde = $this->transactions->getSolde($user);
            return $this->render('pages/dashboard.html.twig', [
                'current_menu' => 'dashboard',
                'user' => $user,
                'balance' => $solde,
                'accounts' => $accounts
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}