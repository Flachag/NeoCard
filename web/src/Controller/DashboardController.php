<?php


namespace App\Controller;

use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DashboardController extends AbstractController{

    /**
     * @var Environment
     */
    private $user;
    private $transactions;
    private $account;


    public function __construct(UserRepository $users, TransactionRepository $transac, AccountRepository $acc){
        $this->user = $users;
        $this->transactions = $transac;
        $this->account = $acc;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(){
        $user = $this->getUser();
        $accounts = $this->account->findBy(['iduser' => $user->getId()]);
        $balance = $this->transactions->getBalance($user);
        return $this->render('pages/dashboard.html.twig', [
            'current_menu' => 'dashboard',
            'user' => $user,
            'balance' => $balance,
            'accounts' => $accounts
        ]);
    }
}