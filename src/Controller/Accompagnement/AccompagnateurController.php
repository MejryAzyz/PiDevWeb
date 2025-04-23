<?php

namespace App\Controller\Accompagnement;

use App\Entity\Accompagnateur;
use App\Entity\Postulation;
use App\Entity\Offreemploi;
use App\Form\AccompagnateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accompagnateur')]
final class AccompagnateurController extends AbstractController
{
    #[Route(name: 'app_accompagnateur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $accompagnateurs = $entityManager
            ->getRepository(Accompagnateur::class)
            ->findAll();

        return $this->render('accompagnateur/index.html.twig', [
            'accompagnateurs' => $accompagnateurs,
        ]);
    }

    #[Route('/new', name: 'app_accompagnateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accompagnateur = new Accompagnateur();
        $form = $this->createForm(AccompagnateurType::class, $accompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set default values for new accompagnateur
            $accompagnateur->setDateInscription(new \DateTime());
            $accompagnateur->setStatut('pending');
            
            $entityManager->persist($accompagnateur);

            // If there's a job offer ID, create a postulation
            $offreId = $request->query->get('offre_id');
            if ($offreId) {
                $offre = $entityManager->getRepository(Offreemploi::class)->find($offreId);
                if ($offre) {
                    $postulation = new Postulation();
                    $postulation->setIdAccompagnateur($accompagnateur);
                    $postulation->setIdOffre($offre);
                    $postulation->setDatePostulation(new \DateTime());
                    $postulation->setStatut('pending');
                    
                    $entityManager->persist($postulation);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Your application has been submitted successfully!');
            return $this->redirectToRoute('app_front_offreemploi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accompagnateur/new.html.twig', [
            'accompagnateur' => $accompagnateur,
            'form' => $form,
            'offre_id' => $request->query->get('offre_id'),
        ]);
    }

    #[Route('/{id_accompagnateur}', name: 'app_accompagnateur_show', methods: ['GET'])]
    public function show(Accompagnateur $accompagnateur): Response
    {
        return $this->render('accompagnateur/show.html.twig', [
            'accompagnateur' => $accompagnateur,
        ]);
    }

    #[Route('/{id_accompagnateur}/edit', name: 'app_accompagnateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accompagnateur $accompagnateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccompagnateurType::class, $accompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_accompagnateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accompagnateur/edit.html.twig', [
            'accompagnateur' => $accompagnateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_accompagnateur}', name: 'app_accompagnateur_delete', methods: ['POST'])]
    public function delete(Request $request, Accompagnateur $accompagnateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accompagnateur->getId_accompagnateur(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accompagnateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accompagnateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
