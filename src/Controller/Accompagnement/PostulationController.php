<?php

namespace App\Controller\Accompagnement;

use App\Entity\Postulation;
use App\Form\PostulationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/postulation')]
final class PostulationController extends AbstractController
{
    #[Route(name: 'app_postulation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $postulations = $entityManager
            ->getRepository(Postulation::class)
            ->findAll();

        return $this->render('postulation/index.html.twig', [
            'postulations' => $postulations,
        ]);
    }

    #[Route('/new', name: 'app_postulation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postulation = new Postulation();
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postulation);
            $entityManager->flush();

            return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('postulation/new.html.twig', [
            'postulation' => $postulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id_postulation}', name: 'app_postulation_show', methods: ['GET'])]
    public function show(Postulation $postulation): Response
    {
        return $this->render('postulation/show.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    #[Route('/{id_postulation}/edit', name: 'app_postulation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Postulation $postulation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('postulation/edit.html.twig', [
            'postulation' => $postulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id_postulation}', name: 'app_postulation_delete', methods: ['POST'])]
    public function delete(Request $request, Postulation $postulation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postulation->getId_postulation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($postulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
    }
}
