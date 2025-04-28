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

#[Route('/planning/accompagnateur')]
final class PlanningAccompagnateurController extends AbstractController{
    #[Route(name: 'app_planning_accompagnateur_index', methods: ['GET'])]
    public function index(PlanningAccompagnateurRepository $planningAccompagnateurRepository): Response
    {
        return $this->render('planning_accompagnateur/index.html.twig', [
            'planning_accompagnateurs' => $planningAccompagnateurRepository->findAll(),
        ]);
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
