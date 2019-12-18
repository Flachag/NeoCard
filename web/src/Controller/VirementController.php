<?php


namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class VirementController extends AbstractController{

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
     * @Route("/virements", name="virements")
     */
    public function index(Request $request): Response{
        $user = $this->get('session')->get('user')[0];
        $accounts = $this->account->findBy(['idutil' => $user->getIdUtil()]);
        $errors = null;
        $success = null;

        $form = $this->createFormBuilder()
            ->add('recepteur', TextType::class)
            ->add('montant', MoneyType::class)
            ->add('libelle', TextType::class)
            ->add('save', SubmitType::class)
            ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $recepteur = $this->account->find($form->get('recepteur')->getData());
            $solde = $this->transactions->getSolde($user);
            if ($recepteur==null) {
                $errors = "Compte inexistant";
            } else if($solde < $form->get('montant')->getData()) {
                $errors = "Solde insufisant";
            } else if($accounts[0]->getIdcompte() == $form->get('recepteur')->getData()){
                $errors = "Vous ne pouvez pas faire un virement vers votre compte actuel.";
            } else {
                $transac = new Transaction();
                $transac->setTypetransac('Virement')
                    ->setLibelle($form->get('libelle')->getData())
                    ->setMontant($form->get('montant')->getData())
                    ->setEmetteur($accounts[0])
                    ->setRecepteur($recepteur)
                    ->setDate(new \DateTime());

                $managerTransac = $this->getDoctrine()->getManager();
                $managerTransac->persist($transac);
                $managerTransac->flush();
                $success = "Le virement s'est fait avec succès !";
            }
        }

        return $this->render('pages/virements.html.twig', [
            'current_menu' => 'virements',
            'errors' => $errors,
            'success' => $success,
            'accounts' => $accounts,
            'form' => $form->createView()]);
    }
}