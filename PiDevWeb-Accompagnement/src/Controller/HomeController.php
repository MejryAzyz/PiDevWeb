<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/back', name: 'homeback')]
    public function index(): Response
    {
        return $this->render('base.html.twig'); // Renders templates/home.html.twig
    }
    #[Route('/front', name: 'homefront')]
    public function front(): Response
    {
        return $this->render('baseFront.html.twig'); // Renders templates/home.html.twig
    }
}

