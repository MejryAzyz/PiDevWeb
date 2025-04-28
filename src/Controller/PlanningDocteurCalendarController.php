<?php

namespace App\Controller;

use App\Entity\PlanningDocteur;
use App\Repository\PlanningDocteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

#[Route('/planning/docteur')]
final class PlanningDocteurCalendarController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/calendar', name: 'app_planning_docteur_calendar', methods: ['GET'])]
    public function calendar(PlanningDocteurRepository $planningDocteurRepository): Response
    {
        return $this->render('planning_docteur/calendar.html.twig', [
            'planning_docteurs' => $planningDocteurRepository->findAll(),
        ]);
    }

    #[Route('/api/{id}/planning', name: 'app_planning_docteur_api', methods: ['GET'])]
    public function getDocteurPlanning(int $id, PlanningDocteurRepository $planningDocteurRepository): JsonResponse
    {
        try {
            $plannings = $planningDocteurRepository->findBy(['docteur' => $id]);
            
            $data = array_map(function($planning) {
                return [
                    'idPlanning' => $planning->getIdPlanning(),
                    'dateJour' => $planning->getDateJour()->format('Y-m-d'),
                    'heureDebut' => $planning->getHeureDebut(),
                    'heureFin' => $planning->getHeureFin()
                ];
            }, $plannings);

            return $this->json($data);
        } catch (\Exception $e) {
            $this->logger->error('Error in getDocteurPlanning: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
} 