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

#[Route('/planning/docteur')]
final class PlanningDocteurController extends AbstractController{
    #[Route(name: 'app_planning_docteur_index', methods: ['GET'])]
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
            'form' => $form,
        ]);
    }

    #[Route('/{id_planning}', name: 'app_planning_docteur_show', methods: ['GET'])]
    public function show(PlanningDocteur $planningDocteur): Response
    {
        return $this->render('planning_docteur/show.html.twig', [
            'planning_docteur' => $planningDocteur,
        ]);
    }

    #[Route('/{id_planning}/edit', name: 'app_planning_docteur_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id_planning}', name: 'app_planning_docteur_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningDocteur $planningDocteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningDocteur->getId_planning(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planningDocteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
