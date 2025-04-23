<?php

namespace App\Controller\planning;

use App\Entity\DossierMedical;
use App\Form\DossierMedicalType;
use App\Repository\DossierMedicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planning/dossier/medical')]
class DossierMedicalController extends AbstractController
{
    #[Route('/', name: 'app_planning_dossier_medical_index', methods: ['GET'])]
    public function index(DossierMedicalRepository $dossierMedicalRepository): Response
    {
        return $this->render('planning/dossier_medical/index.html.twig', [
            'dossier_medicals' => $dossierMedicalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_dossier_medical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierMedical = new DossierMedical();
        $form = $this->createForm(DossierMedicalType::class, $dossierMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossierMedical);
            $entityManager->flush();

            $this->addFlash('success', 'Le dossier médical a été créé avec succès.');
            return $this->redirectToRoute('app_planning_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/dossier_medical/new.html.twig', [
            'dossier_medical' => $dossierMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_dossier_medical_show', methods: ['GET'])]
    public function show(DossierMedical $dossierMedical): Response
    {
        return $this->render('planning/dossier_medical/show.html.twig', [
            'dossier_medical' => $dossierMedical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_dossier_medical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierMedical $dossierMedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierMedicalType::class, $dossierMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le dossier médical a été modifié avec succès.');
            return $this->redirectToRoute('app_planning_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/dossier_medical/edit.html.twig', [
            'dossier_medical' => $dossierMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_dossier_medical_delete', methods: ['POST'])]
    public function delete(Request $request, DossierMedical $dossierMedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossierMedical->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossierMedical);
            $entityManager->flush();
            $this->addFlash('success', 'Le dossier médical a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_planning_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
    }
} 