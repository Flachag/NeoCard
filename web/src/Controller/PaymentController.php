<?php


namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PaymentController extends AbstractController{

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
     * @Route("/virements", name="virements")
     */
    public function index(Request $request): Response{
        $user = $this->getUser();
        $accounts = $this->account->findBy(['id' => $user->getId()]);
        $errors = null;
        $success = null;

        $form = $this->createFormBuilder()
            ->add('receiver', TextType::class)
            ->add('amount', MoneyType::class)
            ->add('label', TextType::class)
            ->add('save', SubmitType::class)
            ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $receiver = $this->account->find($form->get('receiver')->getData());
            $balance = $this->transactions->getBalance($user);
            if ($receiver==null) {
                $errors = "Compte inexistant";
            } else if($balance < $form->get('amount')->getData()) {
                $errors = "Solde insufisant";
            } else if($form->get('amount')->getData() < 0) {
                $errors = "Veuillez rentrer un valeur positive";
            }  else if($accounts[0]->getId() == $form->get('receiver')->getData()){
                $errors = "Vous ne pouvez pas faire un virement vers votre compte actuel";
            } else {
                $transac = new Transaction();
                $transac->setTypetransac('Virement')
                    ->setLabel($form->get('libelle')->getData())
                    ->setAmount($form->get('montant')->getData())
                    ->setIssuer($accounts[0])
                    ->setReceiver($receiver)
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

    /**
     * @param Request $request
     * @return Response
     * @route ("/historique", name="historique")
     */
    public function historique(Request $request): Response{
        $user = $this->getUser();
        $transactions = $this->transactions->getTransactions($user);
        $accounts = $this->account->findBy(['iduser' => $user->getId()]);

        return $this->render('pages/historique.html.twig', [
            'current_menu' => 'dashboard',
            'user' => $user,
            'accounts' => $accounts,
            'transactions' => $transactions
        ]);
    }
}