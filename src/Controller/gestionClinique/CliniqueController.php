<?php

namespace App\Controller\gestionClinique;

use App\Entity\Clinique;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clinique')]
final class CliniqueController extends AbstractController
{
    #[Route(name: 'app_clinique_index', methods: ['GET'])]
    public function index(CliniqueRepository $cliniqueRepository): Response
    {
        return $this->render('clinique/index.html.twig', [
            'cliniques' => $cliniqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clinique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clinique = new Clinique();
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clinique);
            $entityManager->flush();

            return $this->redirectToRoute('app_clinique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clinique/new.html.twig', [
            'clinique' => $clinique,
            'form' => $form,
        ]);
    }

    #[Route('/{id_clinique}', name: 'app_clinique_show', methods: ['GET'])]
    public function show(Clinique $clinique): Response
    {
        return $this->render('clinique/show.html.twig', [
            'clinique' => $clinique,
        ]);
    }

    #[Route('/{id_clinique}/edit', name: 'app_clinique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clinique $clinique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_clinique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clinique/edit.html.twig', [
            'clinique' => $clinique,
            'form' => $form,
        ]);
    }

    #[Route('/{id_clinique}', name: 'app_clinique_delete', methods: ['POST'])]
    public function delete(Request $request, Clinique $clinique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinique->getId_clinique(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($clinique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clinique_index', [], Response::HTTP_SEE_OTHER);
    }

}
