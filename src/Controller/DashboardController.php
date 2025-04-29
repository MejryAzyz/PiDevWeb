<?php

namespace App\Controller;

use App\Repository\AccompagnateurRepository;
use App\Repository\OffreemploiRepository;
use App\Repository\PostulationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        OffreemploiRepository $offreemploiRepository,
        AccompagnateurRepository $accompagnateurRepository,
        PostulationRepository $postulationRepository
    ): Response {
        $offerCount = $offreemploiRepository->count(['etat' => 'active']);
        $accompagnateurPendingCount = $accompagnateurRepository->count(['statut' => 'Pending']);
        $accompagnateurAcceptedCount = $accompagnateurRepository->count(['statut' => 'Accepted']);
        $postulationAcceptedCount = $postulationRepository->count(['statut' => 'Accepted']);
        $postulationRejectedCount = $postulationRepository->count(['statut' => 'Rejected']);
        $postulationPendingCount = $postulationRepository->count(['statut' => 'Pending']);
        $postulations = $postulationRepository->findBy(['statut' => 'Pending'], ['date_postulation' => 'DESC']);


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'offer_count' => $offerCount,
            'accompagnateur_pending_count' => $accompagnateurPendingCount,
            'accompagnateur_accepted_count' => $accompagnateurAcceptedCount,
            'postulation_accepted_count' => $postulationAcceptedCount,
            'postulation_rejected_count' => $postulationRejectedCount,
            'postulation_pending_count' => $postulationPendingCount,
            'postulations' => $postulations,

        ]);
    }
}