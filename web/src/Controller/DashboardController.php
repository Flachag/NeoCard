<?php


namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
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


    public function __construct(UtilisateurRepository $users, TransactionRepository $transac){
        $this->user = $users;
        $this->transactions = $transac;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response{
        $user = $this->get('session')->get('user')[0];
        $solde = null;
        if($user != null) {
            $solde = $this->transactions->getSolde($user);
            return $this->render('pages/dashboard.html.twig', [
                'current_menu' => 'dashboard',
                'user' => $user,
                'solde' => $solde
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}