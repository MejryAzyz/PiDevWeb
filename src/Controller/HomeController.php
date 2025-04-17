<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/aziz', name: 'home')]
    public function index(): Response
    {
        return $this->render('/home/index.html.twig'); // Renders templates/home.html.twig
    }
    #[Route('/aziz1', name: 'home_aziz1')]
    public function aziz1(): Response
    {
        return $this->render('baseFront.html.twig'); // Vous pouvez changer le template si nécessaire
    }
}

