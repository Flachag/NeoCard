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
    public function index()
    {
        $user = $this->getUser();
        $accounts = $this->account->findBy(['iduser' => $user->getId()]);
        $balance = 0;
        foreach ($accounts as $account) {
            $balance += $this->transactions->getBalance($account);
        }

        //Graph
        for($i=0; $i<7; $i++){
            $dates[] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-6+$i, date("Y")));
        }

        foreach ($dates as $date){
            $data[] = $this->transactions->getBalanceAt($account,$date);
        }

        $dates = json_encode($dates);
        $data = json_encode($data);

        //Month balance
        $month = $this->transactions->getBalanceMonth($account,date('m'));
        return $this->render('pages/dashboard.html.twig', [
            'current_menu' => 'dashboard',
            'user' => $user,
            'balance' => $balance,
            'accounts' => $accounts,
            'labels' => $dates,
            'data' => $data,
            'month_balance' => $month
        ]);
    }
}