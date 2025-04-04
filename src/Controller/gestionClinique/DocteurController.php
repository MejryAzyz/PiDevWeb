<?php

namespace App\Controller\gestionClinique;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocteurController extends AbstractController
{
    #[Route('/doctor', name: 'doctor')]
    public function index(): Response
    {
        return $this->render('gestionClinique/doctor.html.twig'); // Renders templates/home.html.twig
    }
}