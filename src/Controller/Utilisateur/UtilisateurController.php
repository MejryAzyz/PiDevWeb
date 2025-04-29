<?php

namespace App\Controller\Utilisateur;

use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UtilisateurType;
use App\Form\EditFormType;
use App\Repository\UtilisateurRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\UserSearchType;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController{
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $searchForm = $this->createForm(UserSearchType::class);
        $searchForm->handleRequest($request);

        $searchData = $searchForm->getData();

        $utilisateurs = $utilisateurRepository->findByFilters(
            $searchData['search'] ?? null,
            $searchData['nationalite'] ?? null,
            $searchData['status'] ?? ''
        );
        
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_utilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id_utilisateur}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_utilisateur}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId_utilisateur(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id_utilisateur}/ban', name: 'app_utilisateur_ban', methods: ['POST'])]
    public function ban(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('ban'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
            $utilisateur->setStatus(0);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été banni avec succès.');
        }

        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route('/{id_utilisateur}/unban', name: 'app_utilisateur_unban', methods: ['POST'])]
    public function unban(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('unban'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
            $utilisateur->setStatus(1);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été débloqué avec succès.');
        }

        return $this->redirectToRoute('app_utilisateur_index');
    }
}