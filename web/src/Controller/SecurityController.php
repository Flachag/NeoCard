<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route ("/inscription", name="security_registration")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setBanned(false);

            $user->setRoles(["ROLE_USER"]);
            $manager->persist($user);
            $manager->flush();

            $account = new Account();
            $account->setLabel('Compte Courant');
            $account->setIduser($user->getId());
            $account->setId(bin2hex(random_bytes(10)));
            $manager->persist($account);
            $manager->flush();
            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $user = $this->getUser();

        if ($user != null && !$user->isBanned()) {
            return $this->redirectToRoute("dashboard");
        } else {
            // get the login error if there is one
            $flash = null;
            if($user != null && $user->isBanned()){
                $flash = "Utilisateur banni";

            }
            $error = $authenticationUtils->getLastAuthenticationError();
            if ($error != null) {
                $code = $error->getCode();
                if ($code == 0) {
                    $flash = "Identifiant ou Mot de Passe incorrect";
                }
            }

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'flash' => $flash,
            ]);
        }
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
    }
}