<?php

namespace App\Controller\planning;

use App\Entity\PlanningAccompagnateur;
use App\Form\PlanningAccompagnateurType;
use App\Repository\PlanningAccompagnateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

#[Route('/planning/accompagnateur')]
final class PlanningAccompagnateurController extends AbstractController{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_planning_accompagnateur_index', methods: ['GET'])]
    public function index(PlanningAccompagnateurRepository $planningAccompagnateurRepository): Response
    {
        return $this->render('planning_accompagnateur/index.html.twig', [
            'planning_accompagnateurs' => $planningAccompagnateurRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'app_planning_accompagnateur_search', methods: ['GET'])]
    public function search(Request $request, PlanningAccompagnateurRepository $planningAccompagnateurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            $this->logger->info('Searching for: ' . $query);
            
            // If query is empty, return all records
            $plannings = empty($query) ? 
                $planningAccompagnateurRepository->findAll() : 
                $planningAccompagnateurRepository->search($query);
            
            if (empty($plannings)) {
                return $this->json([]);
            }

            $data = array_map(function($planning) {
                try {
                    return [
                        'idPlanning' => $planning->getIdPlanning(),
                        'accompagnateurNom' => $planning->getAccompagnateur()->getUsername(),
                        'dateJour' => $planning->getDateJour()->format('Y-m-d'),
                        'heureDebut' => $planning->getHeureDebut(),
                        'heureFin' => $planning->getHeureFin(),
                        'dossierMedical' => $planning->getDossierMedical() ? [
                            'id' => $planning->getDossierMedical()->getId()
                        ] : null,
                        'csrfToken' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $planning->getIdPlanning())->getValue()
                    ];
                } catch (\Exception $e) {
                    $this->logger->error('Error processing planning: ' . $e->getMessage());
                    return null;
                }
            }, $plannings);

            // Filter out any null values from failed processing
            $data = array_filter($data);

            $this->logger->info('Found ' . count($data) . ' results');
            return $this->json($data);
        } catch (\Exception $e) {
            $this->logger->error('Error in search: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/search/suggestions', name: 'app_planning_accompagnateur_search_suggestions', methods: ['GET'])]
    public function searchSuggestions(Request $request, PlanningAccompagnateurRepository $planningAccompagnateurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            if (empty($query)) {
                return $this->json([]);
            }

            $this->logger->info('Getting suggestions for: ' . $query);
            
            $suggestions = $planningAccompagnateurRepository->getSearchSuggestions($query);
            
            if (empty($suggestions)) {
                return $this->json([]);
            }

            $this->logger->info('Found ' . count($suggestions) . ' suggestions');
            return $this->json($suggestions);
        } catch (\Exception $e) {
            $this->logger->error('Error in searchSuggestions: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/new', name: 'app_planning_accompagnateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planningAccompagnateur = new PlanningAccompagnateur();
        $form = $this->createForm(PlanningAccompagnateurType::class, $planningAccompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planningAccompagnateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_accompagnateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning_accompagnateur/new.html.twig', [
            'planning_accompagnateur' => $planningAccompagnateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_planning}', name: 'app_planning_accompagnateur_show', methods: ['GET'])]
    public function show(PlanningAccompagnateur $planningAccompagnateur): Response
    {
        return $this->render('planning_accompagnateur/show.html.twig', [
            'planning_accompagnateur' => $planningAccompagnateur,
        ]);
    }

    #[Route('/{id_planning}/edit', name: 'app_planning_accompagnateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlanningAccompagnateur $planningAccompagnateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningAccompagnateurType::class, $planningAccompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_accompagnateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning_accompagnateur/edit.html.twig', [
            'planning_accompagnateur' => $planningAccompagnateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_planning}', name: 'app_planning_accompagnateur_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningAccompagnateur $planningAccompagnateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningAccompagnateur->getId_planning(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planningAccompagnateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_accompagnateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
