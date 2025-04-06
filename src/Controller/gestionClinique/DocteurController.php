<?php

namespace App\Controller\gestionClinique;

use App\Entity\Docteur;
use App\Form\DocteurType;
use App\Repository\DocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/docteur')]
final class DocteurController extends AbstractController
{
    #[Route(name: 'app_docteur_index', methods: ['GET'])]
    public function index(DocteurRepository $docteurRepository): Response
    {
        return $this->render('docteur/index.html.twig', [
            'docteurs' => $docteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_docteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $docteur = new Docteur();
        $form = $this->createForm(DocteurType::class, $docteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($docteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_docteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('docteur/new.html.twig', [
            'docteur' => $docteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_docteur}', name: 'app_docteur_show', methods: ['GET'])]
    public function show(Docteur $docteur): Response
    {
        return $this->render('docteur/show.html.twig', [
            'docteur' => $docteur,
        ]);
    }

    #[Route('/{id_docteur}/edit', name: 'app_docteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Docteur $docteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocteurType::class, $docteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le docteur a été modifié avec succès.');


            return $this->redirectToRoute('app_docteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('docteur/edit.html.twig', [
            'docteur' => $docteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_docteur}', name: 'app_docteur_delete', methods: ['POST'])]
    public function delete(Request $request, Docteur $docteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$docteur->getId_docteur(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($docteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_docteur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('{id_clinique}/docteurs', name: 'app_clinique_docteurs', methods: ['GET'])]
    public function showDoctorsByClinique(int $id_clinique, DocteurRepository $docteurRepository): Response
     {
      // Récupérer tous les docteurs pour la clinique donnée
       $docteurs = $docteurRepository->findBy(['clinique' => $id_clinique]);

       return $this->render('clinique/show.html.twig', [
        'docteurs' => $docteurs,
      ]);
    }

}
