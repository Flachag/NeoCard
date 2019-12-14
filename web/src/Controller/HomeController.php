<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController{

    /**
     * @var Environment
     */
    private $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): Response{
        return new Response($this->twig->render('pages/home.html.twig'));
    }
}