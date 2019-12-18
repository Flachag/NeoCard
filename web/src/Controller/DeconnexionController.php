<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeconnexionController extends AbstractController{

    /**
     * @Route("/deconnect", name="deconnect")
     */
    public function deconnect(){
        $this->get('session')->remove('user');
        return $this->redirectToRoute('home');
    }
}