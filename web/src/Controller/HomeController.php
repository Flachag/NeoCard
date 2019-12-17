<?php


namespace App\Controller;


use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController{

    /**
     * @var Environment
     */
    private $repository;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig, UtilisateurRepository $repository){
        $this->repository = $repository;
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response{
        $form = $this->createFormBuilder()
                     ->add('nom', TextType::class)
                     ->add('mdp', PasswordType::class)
                     ->add('save', SubmitType::class)
                     ->setMethod('POST')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getRepository(Utilisateur::class);
            $user = $manager->findOneBy(['nom' => $form->get('nom')->getData()]);
            if ($user!=null) {
                $this->get('session')->set('user', $user);
                return $this->redirectToRoute('dashboard');
            }
        }
        return $this->render('pages/selection_compte.html.twig', [
                                    'current_menu' => 'index',
                                    'form' => $form->createView()]);
    }
}