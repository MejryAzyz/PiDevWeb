<?php

namespace App\Controller;

use App\Entity\Offreemploi;
use App\Form\OffreemploiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/offreemploi')]
final class OffreemploiController extends AbstractController
{
    #[Route(name: 'app_offreemploi_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offreemplois = $entityManager
            ->getRepository(Offreemploi::class)
            ->findAll();

        $totalOffers = count($offreemplois);
        $activeOffers = count(array_filter($offreemplois, fn($offer) => $offer->getEtat() === 'active'));
        $closedOffers = $totalOffers - $activeOffers;

        return $this->render('offreemploi/index.html.twig', [
            'offreemplois' => $offreemplois,
            'totalOffers' => $totalOffers,
            'activeOffers' => $activeOffers,
            'closedOffers' => $closedOffers,
        ]);
    }
    #[Route('/new', name: 'app_offreemploi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offreemploi = new Offreemploi();
        $form = $this->createForm(OffreemploiType::class, $offreemploi);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Always check isValid() after isSubmitted()
            if ($form->isValid()) {
                try {
                    // Handle file upload
                    $imageFile = $form->get('imageurl')->getData();
                    if ($imageFile) {
                        $newFilename = uniqid().'.'.$imageFile->guessExtension();
                        $imageFile->move(
                            $this->getParameter('offers_directory'),
                            $newFilename
                        );
                        $offreemploi->setImageurl($newFilename);
                    }

                    $entityManager->persist($offreemploi);
                    $entityManager->flush();

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'status' => 'success',
                            'message' => 'Offre créée avec succès!'
                        ]);
                    }

                    $this->addFlash('success', 'Offre créée avec succès!');
                    return $this->redirectToRoute('app_offreemploi_index');

                } catch (\Exception $e) {
                    $errorResponse = [
                        'status' => 'error',
                        'message' => 'Erreur technique: '.$e->getMessage()
                    ];

                    return $request->isXmlHttpRequest()
                        ? new JsonResponse($errorResponse, 500)
                        : $this->render('offreemploi/new.html.twig', [
                            'form' => $form->createView(),
                            'error' => $errorResponse['message']
                        ]);
                }
            } else {
                // Form is invalid
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'status' => 'invalid',
                        'errors' => $errors
                    ], 422);
                }
            }
        }

        // For GET requests or when form is not submitted
        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

        return $this->render("offreemploi/{$template}", [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_offreemploi_show', methods: ['GET'])]
    public function show(Offreemploi $offreemploi): Response
    {
        return $this->render('offreemploi/show.html.twig', [
            'offreemploi' => $offreemploi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offreemploi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreemploiType::class, $offreemploi);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Handle file upload if needed
                $imageFile = $form->get('imageurl')->getData();
                if ($imageFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('offers_directory'),
                            $newFilename
                        );
                        // Remove old image if exists
                        if ($offreemploi->getImageurl()) {
                            $oldImage = $this->getParameter('offers_directory').'/'.$offreemploi->getImageurl();
                            if (file_exists($oldImage)) {
                                unlink($oldImage);
                            }
                        }
                        $offreemploi->setImageurl($newFilename);
                    } catch (FileException $e) {
                        $errorMessage = 'Échec du téléchargement de l\'image : '.$e->getMessage();

                        if ($request->isXmlHttpRequest()) {
                            return new JsonResponse([
                                'status' => 'error',
                                'message' => $errorMessage
                            ], 400);
                        }

                        $this->addFlash('error', $errorMessage);
                    }
                }

                try {
                    $entityManager->flush();

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'status' => 'success',
                            'message' => 'Offre mise à jour avec succès!'
                        ]);
                    }

                    $this->addFlash('success', 'Offre mise à jour avec succès');
                    return $this->redirectToRoute('app_offreemploi_index', [], Response::HTTP_SEE_OTHER);

                } catch (\Exception $e) {
                    $errorMessage = 'Erreur lors de la mise à jour : '.$e->getMessage();

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'status' => 'error',
                            'message' => $errorMessage
                        ], 500);
                    }

                    $this->addFlash('error', $errorMessage);
                }
            } else {
                // Form is invalid
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'status' => 'invalid',
                        'errors' => $errors,
                        'message' => 'Veuillez corriger les erreurs dans le formulaire'
                    ], 422);
                }
            }
        }

        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'edit.html.twig';

        return $this->render("offreemploi/{$template}", [
            'offreemploi' => $offreemploi,
            'form' => $form->createView(),
        ], $request->isXmlHttpRequest() ? new Response('', 422) : null);
    }
    #[Route('/{id}', name: 'app_offreemploi_delete', methods: ['POST'])]
    public function delete(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offreemploi->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($offreemploi);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'status' => 'success',
                    'message' => 'Job offer deleted successfully'
                ]);
            }
        }

        return $this->redirectToRoute('app_offreemploi_index', [], Response::HTTP_SEE_OTHER);
    }
}