<?php

namespace App\Controller\Accompagnement;

use App\Entity\Offreemploi;
use App\Form\OffreemploiType;
use App\Repository\OffreemploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/offreemploi')]
class OffreemploiController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/filter-options', name: 'app_offreemploi_filter_options', methods: ['GET'])]
    public function getFilterOptions(OffreemploiRepository $repository): JsonResponse
    {
        $offers = $repository->findAll();
        
        // Get unique values for each filter
        $jobTypes = array_unique(array_map(fn($o) => $o->getTypeposte(), $offers));
        $contractTypes = array_unique(array_map(fn($o) => $o->getTypecontrat(), $offers));
        $locations = array_unique(array_map(fn($o) => $o->getEmplacement(), $offers));
        
        return new JsonResponse([
            'jobTypes' => array_values($jobTypes),
            'contractTypes' => array_values($contractTypes),
            'locations' => array_values($locations),
            'statuses' => ['active', 'inactive']
        ]);
    }

    #[Route('/', name: 'app_offreemploi_index', methods: ['GET'])]
    public function index(Request $request, OffreemploiRepository $offreemploiRepository): Response
    {
        $jobType = $request->query->get('jobType');
        $contractType = $request->query->get('contractType');
        $location = $request->query->get('location');
        $status = $request->query->get('status');

        $queryBuilder = $offreemploiRepository->createQueryBuilder('o');

        if ($jobType) {
            $queryBuilder->andWhere('o.typeposte = :jobType')
                        ->setParameter('jobType', $jobType);
        }
        if ($contractType) {
            $queryBuilder->andWhere('o.typecontrat = :contractType')
                        ->setParameter('contractType', $contractType);
        }
        if ($location) {
            $queryBuilder->andWhere('o.emplacement = :location')
                        ->setParameter('location', $location);
        }
        if ($status) {
            $queryBuilder->andWhere('o.etat = :status')
                        ->setParameter('status', $status);
        }

        $offers = $queryBuilder->getQuery()->getResult();

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'data' => array_map(function($offer) {
                    return [
                        'id' => $offer->getId(),
                        'titre' => $offer->getTitre(),
                        'typeposte' => $offer->getTypeposte(),
                        'typecontrat' => $offer->getTypecontrat(),
                        'emplacement' => $offer->getEmplacement(),
                        'datepublication' => $offer->getDatepublication() ? $offer->getDatepublication()->format('Y-m-d') : '',
                        'etat' => $offer->getEtat(),
                        'imageurl' => $offer->getImageurl()
                    ];
                }, $offers),
                'stats' => [
                    'total' => count($offers),
                    'active' => count(array_filter($offers, fn($o) => $o->getEtat() === 'active')),
                    'inactive' => count(array_filter($offers, fn($o) => $o->getEtat() !== 'active'))
                ]
            ]);
        }

        return $this->render('offreemploi/index.html.twig', [
            'offreemplois' => $offers,
        ]);
    }

    #[Route('/new', name: 'app_offreemploi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $offreemploi = new Offreemploi();
        $form = $this->createForm(OffreemploiType::class, $offreemploi);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $request->isMethod('GET')) {
            return $this->render('offreemploi/_form.html.twig', [
                'form' => $form->createView()
            ]);
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    /** @var UploadedFile $imageFile */
                    $imageFile = $form->get('imageurl')->getData();
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                        // Create directory if it doesn't exist
                        $targetDirectory = $this->getParameter('images_directory');
                        if (!file_exists($targetDirectory)) {
                            mkdir($targetDirectory, 0777, true);
                        }

                        try {
                            $imageFile->move($targetDirectory, $newFilename);
                            $offreemploi->setImageurl($newFilename);
                        } catch (FileException $e) {
                            return $this->json([
                                'status' => 'error',
                                'message' => 'Failed to upload image.',
                                'errors' => ['imageurl' => $e->getMessage()]
                            ]);
                        }
                    }

                    $entityManager->persist($offreemploi);
                    $entityManager->flush();

                    return $this->json([
                        'status' => 'success',
                        'message' => 'Job offer created successfully!'
                    ]);
                } catch (\Exception $e) {
                    return $this->json([
                        'status' => 'error',
                        'message' => 'An error occurred while saving the job offer.',
                        'errors' => ['form' => $e->getMessage()]
                    ]);
                }
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $fieldName = $error->getOrigin() ? $error->getOrigin()->getName() : 'general';
                    if (!isset($errors[$fieldName])) {
                        $errors[$fieldName] = [];
                    }
                    $errors[$fieldName][] = $error->getMessage();
                }

                return $this->json([
                    'status' => 'error',
                    'message' => 'Please fix the form errors.',
                    'errors' => $errors
                ]);
            }
        }

        return $this->render('offreemploi/new.html.twig', [
            'offreemploi' => $offreemploi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offreemploi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(OffreemploiType::class, $offreemploi);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $request->isMethod('GET')) {
            return $this->render('offreemploi/_form.html.twig', [
                'form' => $form->createView(),
                'offreemploi' => $offreemploi
            ]);
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    /** @var UploadedFile $imageFile */
                    $imageFile = $form->get('imageurl')->getData();
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                        // Create directory if it doesn't exist
                        $targetDirectory = $this->getParameter('images_directory');
                        if (!file_exists($targetDirectory)) {
                            mkdir($targetDirectory, 0777, true);
                        }

                        try {
                            // Delete old image if it exists
                            $oldImage = $offreemploi->getImageurl();
                            if ($oldImage) {
                                $oldImagePath = $targetDirectory.'/'.$oldImage;
                                if (file_exists($oldImagePath)) {
                                    unlink($oldImagePath);
                                }
                            }

                            $imageFile->move($targetDirectory, $newFilename);
                            $offreemploi->setImageurl($newFilename);
                        } catch (FileException $e) {
                            return $this->json([
                                'status' => 'error',
                                'message' => 'Failed to upload image.',
                                'errors' => ['imageurl' => $e->getMessage()]
                            ]);
                        }
                    }

                    $entityManager->flush();

                    return $this->json([
                        'status' => 'success',
                        'message' => 'Job offer updated successfully!'
                    ]);
                } catch (\Exception $e) {
                    return $this->json([
                        'status' => 'error',
                        'message' => 'An error occurred while updating the job offer.',
                        'errors' => ['form' => $e->getMessage()]
                    ]);
                }
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $fieldName = $error->getOrigin() ? $error->getOrigin()->getName() : 'general';
                    if (!isset($errors[$fieldName])) {
                        $errors[$fieldName] = [];
                    }
                    $errors[$fieldName][] = $error->getMessage();
                }

                return $this->json([
                    'status' => 'error',
                    'message' => 'Please fix the form errors.',
                    'errors' => $errors
                ]);
            }
        }

        return $this->render('offreemploi/edit.html.twig', [
            'offreemploi' => $offreemploi,
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

    #[Route('/{id}', name: 'app_offreemploi_delete', methods: ['POST'])]
    public function delete(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offreemploi->getId(), $request->request->get('_token'))) {
            try {
                // Delete image file if exists
                $imageFilename = $offreemploi->getImageurl();
                if ($imageFilename) {
                    $imagePath = $this->getParameter('offers_directory').'/'.$imageFilename;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $entityManager->remove($offreemploi);
                $entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    return $this->json([
                        'status' => 'success',
                        'message' => 'Job offer deleted successfully!'
                    ]);
                }
            } catch (\Exception $e) {
                $this->logger->error('Error deleting job offer: ' . $e->getMessage());
                if ($request->isXmlHttpRequest()) {
                    return $this->json([
                        'status' => 'error',
                        'message' => 'Failed to delete job offer',
                        'errors' => ['general' => $e->getMessage()]
                    ], 500);
                }
            }
        } else if ($request->isXmlHttpRequest()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid token',
                'errors' => ['token' => 'Invalid CSRF token']
            ], 400);
        }

        return $this->redirectToRoute('app_offreemploi_index');
    }

    #[Route('/{id}/toggle-status', name: 'app_offreemploi_toggle_status', methods: ['POST'])]
    public function toggleStatus(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
    {
        if ($request->isXmlHttpRequest()) {
            try {
                // Toggle the status
                $newStatus = $offreemploi->getEtat() === 'active' ? 'inactive' : 'active';
                $offreemploi->setEtat($newStatus);
                
                $entityManager->flush();
                
                return new JsonResponse([
                    'status' => 'success',
                    'message' => 'Job offer status updated successfully!',
                    'newStatus' => $newStatus
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Error updating offer status: ' . $e->getMessage());
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Failed to update job offer status'
                ], 500);
            }
        }

        return new Response('', 400);
    }
}