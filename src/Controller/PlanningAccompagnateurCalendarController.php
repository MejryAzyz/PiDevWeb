<?php

namespace App\Controller;

use App\Entity\PlanningAccompagnateur;
use App\Repository\PlanningAccompagnateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/planning/accompagnateur')]
final class PlanningAccompagnateurCalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_planning_accompagnateur_calendar', methods: ['GET'])]
    public function calendar(PlanningAccompagnateurRepository $planningAccompagnateurRepository): Response
    {
        return $this->render('planning_accompagnateur/calendar.html.twig', [
            'planning_accompagnateurs' => $planningAccompagnateurRepository->findAll(),
        ]);
    }

    #[Route('/api/accompagnateur/{id}/planning', name: 'app_planning_accompagnateur_api', methods: ['GET'])]
    public function getAccompagnateurPlanning(int $id, PlanningAccompagnateurRepository $planningAccompagnateurRepository): JsonResponse
    {
        try {
            $plannings = $planningAccompagnateurRepository->findBy(['accompagnateur' => $id]);
            
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
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
} 