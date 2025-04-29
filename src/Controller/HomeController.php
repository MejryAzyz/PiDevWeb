<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function redirectToAziz1(): Response
    {
        return $this->redirectToRoute('home_aziz1');
    }

   
    #[Route('/aziz1', name: 'home_aziz1')]
    public function aziz1(): Response
    {
        return $this->render('baseFront.html.twig'); 
    }
}