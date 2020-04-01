<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profil", name="profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function getProfile(Request $request, UserPasswordEncoderInterface $encoder){
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $usr = $entityManager->getRepository(User::class)->find($user->getId());

        $managerAcc = $this->getDoctrine()->getRepository(Account::class);
        $accounts = $managerAcc->findBy(['iduser' => $user->getId()]);

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        $data = $form->getData();
        $success = null;
        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            if(password_verify($data->confirm_password, $data->getPassword()) && $data->new_password == $data->confirm_new_password){
                $hash = $encoder->encodePassword($usr, $data->new_password);
                $usr->setPassword($hash);
                $entityManager->flush();
                $success = "Mot de passe changé avec succès !";
            } else {
                $error = "Mot de passe incorrect ou non identiques";
            }
        }
        return $this->render('pages/profile.html.twig', [
            'current_menu' => 'dashboard',
            'user' => $user,
            'accounts' => $accounts,
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error,
        ]);
    }

}