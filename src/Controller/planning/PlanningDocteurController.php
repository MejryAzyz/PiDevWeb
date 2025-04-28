<?php

namespace App\Controller\planning;

use App\Entity\PlanningDocteur;
use App\Form\PlanningDocteurType;
use App\Repository\PlanningDocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

#[Route('/planning/docteur')]
final class PlanningDocteurController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/search', name: 'app_planning_docteur_search', methods: ['GET'])]
    public function search(Request $request, PlanningDocteurRepository $planningDocteurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            $this->logger->info('Searching for: ' . $query);
            
            // If query is empty, return all records
            $plannings = empty($query) ? 
                $planningDocteurRepository->findAll() : 
                $planningDocteurRepository->search($query);
            
            if (empty($plannings)) {
                return $this->json([]);
            }

            $data = array_map(function($planning) {
                try {
                    return [
                        'idPlanning' => $planning->getIdPlanning(),
                        'docteurNom' => $planning->getDocteur()->getNom(),
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

    #[Route('/search/suggestions', name: 'app_planning_docteur_search_suggestions', methods: ['GET'])]
    public function searchSuggestions(Request $request, PlanningDocteurRepository $planningDocteurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            if (empty($query)) {
                return $this->json([]);
            }

            $this->logger->info('Getting suggestions for: ' . $query);
            
            $suggestions = $planningDocteurRepository->getSearchSuggestions($query);
            
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

    #[Route('/', name: 'app_planning_docteur_index', methods: ['GET'])]
    public function index(PlanningDocteurRepository $planningDocteurRepository): Response
    {
        return $this->render('planning_docteur/index.html.twig', [
            'planning_docteurs' => $planningDocteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_docteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planningDocteur = new PlanningDocteur();
        $form = $this->createForm(PlanningDocteurType::class, $planningDocteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planningDocteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning_docteur/new.html.twig', [
            'planning_docteur' => $planningDocteur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_docteur_show', methods: ['GET'])]
    public function show(PlanningDocteur $planningDocteur): Response
    {
        return $this->render('planning_docteur/show.html.twig', [
            'planning_docteur' => $planningDocteur,
        ]);
    }

    #[Route('/{idPlanning}/edit', name: 'app_planning_docteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlanningDocteur $planningDocteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningDocteurType::class, $planningDocteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning_docteur/edit.html.twig', [
            'planning_docteur' => $planningDocteur,
            'form' => $form,
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_docteur_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningDocteur $planningDocteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningDocteur->getIdPlanning(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planningDocteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
