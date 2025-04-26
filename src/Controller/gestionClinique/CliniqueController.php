<?php

namespace App\Controller\gestionClinique;

use App\Entity\Clinique;
use App\Entity\Clinique_photos;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use App\Repository\DocteurRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;

#[Route('/clinique')]
final class CliniqueController extends AbstractController
{
    #[Route(name: 'app_clinique_index', methods: ['GET'])]
    public function index(CliniqueRepository $cliniqueRepository): Response
    {
        $totalCliniques = count($cliniqueRepository->findAll());

        // Clinique avec le prix le plus bas
        $cliniqueBasPrix = $cliniqueRepository->findOneBy([], ['prix' => 'ASC']);
        $cliniqueHautPrix = $cliniqueRepository->findOneBy([], ['prix' => 'DESC']);
        return $this->render('clinique/index.html.twig', [
            'cliniques' => $cliniqueRepository->findAll(),
            'totalCliniques' => $totalCliniques,
            'cliniqueBasPrix' => $cliniqueBasPrix,
            'cliniqueHautPrix' => $cliniqueHautPrix,
        ]);
    }

    #[Route('/new', name: 'app_clinique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clinique = new Clinique();
        $form = $this->createForm(CliniqueType::class, $clinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                error_log('=== CLINIQUE FORM SUBMISSION START ===');
                error_log('Form submitted and valid');
                
                
                $entityManager->persist($clinique);
                $entityManager->flush();
                error_log('Clinique saved with ID: ' . $clinique->getId_clinique());

                
                $allRequestData = $request->request->all();
                error_log('All form data: ' . print_r($allRequestData, true));
                
                
                $photoIdsJson = $request->request->get('photo_ids');
                error_log('Received photo_ids JSON: ' . ($photoIdsJson ?? 'none'));

                $photoIds = [];
                $foundPhotoIds = false;
                
                
                if ($photoIdsJson && !empty($photoIdsJson)) {
                    try {
                        $photoIds = json_decode($photoIdsJson, true);
                        if (is_array($photoIds) && count($photoIds) > 0) {
                            $foundPhotoIds = true;
                            error_log('Found ' . count($photoIds) . ' photo IDs in the form data');
                        }
                    } catch (\Exception $e) {
                        error_log('Error processing photo IDs from JSON: ' . $e->getMessage());
                    }
                }
                
                
                if (!$foundPhotoIds) {
                    error_log('No photo IDs found in form data, looking for recent uploads as fallback');
                    
                    
                    $fiveMinutesAgo = new \DateTime('-5 minutes');
                    $recentPhotos = $entityManager->getRepository(Clinique_photos::class)
                        ->createQueryBuilder('p')
                        ->where('p.clinique_id IS NULL')
                        ->andWhere('p.uploaded_at >= :time')
                        ->setParameter('time', $fiveMinutesAgo)
                        ->orderBy('p.uploaded_at', 'DESC')
                        ->getQuery()
                        ->getResult();
                    
                    if (count($recentPhotos) > 0) {
                        error_log('Found ' . count($recentPhotos) . ' recently uploaded photos with null clinique_id');
                        $photoIds = array_map(function($photo) {
                            return $photo->getId_photo();
                        }, $recentPhotos);
                        error_log('Using recent photo IDs: ' . implode(', ', $photoIds));
                        $foundPhotoIds = true;
                    } else {
                        error_log('No recent photos found');
                    }
                }
                
                
                if ($foundPhotoIds && count($photoIds) > 0) {
                    error_log('Processing ' . count($photoIds) . ' photo IDs');
                    $updatedPhotoCount = 0;
                    
                    
                    $connection = $entityManager->getConnection();
                    $stmt = $connection->prepare('SELECT id_photo, clinique_id FROM clinique_photos WHERE id_photo IN (' . implode(',', $photoIds) . ')');
                    $result = $stmt->executeQuery();
                    $rows = $result->fetchAllAssociative();
                    error_log('Current DB state for photos: ' . print_r($rows, true));
                    
                    foreach ($photoIds as $photoId) {
                        error_log('Processing photo ID: ' . $photoId);
                        $photo = $entityManager->getRepository(Clinique_photos::class)->find($photoId);
                        
                        if ($photo) {
                            error_log('Found photo with ID: ' . $photoId);
                            $oldCliniqueId = $photo->getCliniqueId() ? $photo->getCliniqueId()->getId_clinique() : 'null';
                            error_log('Current clinique_id: ' . $oldCliniqueId);
                            
                            
                            $photo->setCliniqueId($clinique);
                            $entityManager->persist($photo);
                            
                           
                            $newCliniqueId = $photo->getCliniqueId() ? $photo->getCliniqueId()->getId_clinique() : 'null';
                            error_log('New clinique_id set to: ' . $newCliniqueId);
                            
                            $updatedPhotoCount++;
                        } else {
                            error_log('Could not find photo with ID: ' . $photoId);
                        }
                    }
                    
                    if ($updatedPhotoCount > 0) {
                    
                        error_log('Flushing ' . $updatedPhotoCount . ' photo updates to database');
            $entityManager->flush();

                        
                        $stmt = $connection->prepare('SELECT id_photo, clinique_id FROM clinique_photos WHERE id_photo IN (' . implode(',', $photoIds) . ')');
                        $result = $stmt->executeQuery();
                        $updatedRows = $result->fetchAllAssociative();
                        error_log('DB state after updates: ' . print_r($updatedRows, true));
                        
                        
                        $anyNullCliniqueIds = false;
                        foreach ($updatedRows as $row) {
                            if (is_null($row['clinique_id'])) {
                                $anyNullCliniqueIds = true;
                                break;
                            }
                        }
                        
                        if ($anyNullCliniqueIds) {
                            error_log('Some photos still have null clinique_id, trying direct SQL update');
                            try {
                                $cliniqueId = $clinique->getId_clinique();
                                $photoIdsStr = implode(',', $photoIds);
                                $sql = "UPDATE clinique_photos SET clinique_id = $cliniqueId WHERE id_photo IN ($photoIdsStr)";
                                error_log('Executing SQL: ' . $sql);
                                $connection->executeStatement($sql);
                                
                                
                                $stmt = $connection->prepare('SELECT id_photo, clinique_id FROM clinique_photos WHERE id_photo IN (' . implode(',', $photoIds) . ')');
                                $result = $stmt->executeQuery();
                                $finalRows = $result->fetchAllAssociative();
                                error_log('DB state after direct SQL update: ' . print_r($finalRows, true));
                            } catch (\Exception $e) {
                                error_log('Error in direct SQL update: ' . $e->getMessage());
                            }
                        }
                        
                        error_log('Successfully associated ' . $updatedPhotoCount . ' photos with clinique');
                    } else {
                        error_log('No photos were associated with the clinique');
                    }
                } else {
                    error_log('No photo_ids found in request');
                }
                
                error_log('=== CLINIQUE FORM SUBMISSION END ===');
            return $this->redirectToRoute('app_clinique_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                error_log('Error in new clinique: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                throw $e;
            }
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

    // Nouvelle route pour le front-office (client)
    // #[Route('/front', name: 'app_clinique_front', methods: ['GET'])]
    // public function front(CliniqueRepository $cliniqueRepository): Response
    // {
    //     return $this->render('clinique/indexFront.html.twig', [
    //         'cliniques' => $cliniqueRepository->findAll(),
    //     ]);
    // }

     // ➕ Méthode front pour afficher toutes les cliniques
     #[Route('/front/liste/{page}', name: 'app_clinique_front_index', methods: ['GET'])]
     public function indexFront(Request $request, CliniqueRepository $cliniqueRepository, SpecialiteRepository $specialiteRepository, int $page = 1): Response
     {
         // Nombre d'éléments par page
         $limit = 6;

         // Récupérer les prix min et max de toutes les cliniques
         $priceRange = $cliniqueRepository->createQueryBuilder('c')
             ->select('MIN(c.prix) as min_price, MAX(c.prix) as max_price')
             ->getQuery()
             ->getOneOrNullResult();

         $minPrice = $priceRange['min_price'] ?? 0;
         $maxPrice = $priceRange['max_price'] ?? 1000;

         // Récupérer les filtres depuis la requête
         $priceFilter = $request->query->get('price_range');
         $paysFilter = $request->query->get('pays');
         $specialtyFilter = $request->query->get('specialty');
         $sortFilter = $request->query->get('sort', 'recommande'); 

         $currentMinPrice = $minPrice;
         $currentMaxPrice = $maxPrice;

         // Créer le QueryBuilder de base
         $queryBuilder = $cliniqueRepository->createQueryBuilder('c')
             ->leftJoin('c.cliniquePhotos', 'photos')
             ->addSelect('photos')
             ->leftJoin('c.docteurs', 'd')
             ->leftJoin('d.specialite', 's')
             ->groupBy('c.id_clinique');

         //  le tri
         switch ($sortFilter) {
             case 'prix_asc':
                 $queryBuilder->orderBy('c.prix', 'ASC');
                 break;
             case 'prix_desc':
                 $queryBuilder->orderBy('c.prix', 'DESC');
                 break;
             case 'docteurs':
                 $queryBuilder->addSelect('COUNT(d.id_docteur) as HIDDEN docteurCount')
                            ->orderBy('docteurCount', 'DESC');
                 break;
             case 'recommande':
             default:
                 
                 $queryBuilder->addSelect('COUNT(d.id_docteur) as HIDDEN docteurCount')
                            ->orderBy('docteurCount', 'DESC')
                            ->addOrderBy('c.prix', 'ASC');
                 break;
         }

         // Appliquer le filtre de prix 
         if ($priceFilter) {
             list($currentMinPrice, $currentMaxPrice) = array_map('floatval', explode(',', $priceFilter));
             $queryBuilder
                 ->andWhere('c.prix >= :minPrice')
                 ->andWhere('c.prix <= :maxPrice')
                 ->setParameter('minPrice', $currentMinPrice)
                 ->setParameter('maxPrice', $currentMaxPrice);
         }

         // le filtre de pays 
         if ($paysFilter) {
             $queryBuilder
                 ->andWhere('c.adresse LIKE :pays')
                 ->setParameter('pays', '%' . $paysFilter . '%');
         }

         //  le filtre de spécialité 
         if ($specialtyFilter) {
             $queryBuilder
                 ->andWhere('s.id_specialite = :specialtyId')
                 ->setParameter('specialtyId', $specialtyFilter);
         }

         $query = $queryBuilder->getQuery();

         
         $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

         
         $totalItems = count($paginator);
         $totalPages = ceil($totalItems / $limit);

         
         if ($page < 1) {
             $page = 1;
         }
         if ($page > $totalPages && $totalPages > 0) {
             $page = $totalPages;
         }

         
         $paginator
             ->getQuery()
             ->setFirstResult($limit * ($page - 1))
             ->setMaxResults($limit);

         return $this->render('clinique/indexFront.html.twig', [
             'cliniques' => $paginator,
             'totalPages' => $totalPages,
             'currentPage' => $page,
             'limit' => $limit,
             'min_price' => $minPrice,
             'max_price' => $maxPrice,
             'current_min_price' => $currentMinPrice,
             'current_max_price' => $currentMaxPrice,
             'specialites' => $specialiteRepository->findAll(),
         ]);
     }
    

     //upload image
     #[Route('/upload-photo', name: 'app_clinique_upload_photo', methods: ['POST'])]
    public function uploadPhoto(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Handle OPTIONS requests for CORS preflight
        if ($request->isMethod('OPTIONS')) {
            return new JsonResponse([], Response::HTTP_OK);
        }
        
        // Check if this is a test request without a file
        if (!$request->files->has('file') && $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return new JsonResponse(['message' => 'Upload endpoint is working'], Response::HTTP_OK);
        }
        
        try {
            $file = $request->files->get('file');
            
            if (!$file) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Aucun fichier téléversé'
                ], Response::HTTP_BAD_REQUEST);
            }

            error_log('Received file: ' . $file->getClientOriginalName());
            error_log('File size: ' . $file->getSize());
            error_log('File mime type: ' . $file->getMimeType());

        // Validation du fichier
        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                error_log('Invalid file type: ' . $file->getMimeType());
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Type de fichier non autorisé (JPG ou PNG uniquement)'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Générer un nom unique et déplacer le fichier
            $newFilename = uniqid() . '.' . $file->guessExtension();
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/cliniques';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                error_log('Created upload directory: ' . $uploadDir);
            }

            try {
                $file->move($uploadDir, $newFilename);
                error_log('File moved successfully to: ' . $uploadDir . '/' . $newFilename);
            } catch (\Exception $e) {
                error_log('Error moving file: ' . $e->getMessage());
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Erreur lors du déplacement du fichier: ' . $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            try {
                // Create new Clinique_photos entry
                $cliniquePhoto = new Clinique_photos();
                
                // Generate new ID
                $lastPhoto = $entityManager->getRepository(Clinique_photos::class)
                    ->findOneBy([], ['id_photo' => 'DESC']);
                $newId = $lastPhoto ? $lastPhoto->getId_photo() + 1 : 1;
                
                $cliniquePhoto->setId_photo($newId);
                $cliniquePhoto->setPhoto_url('/uploads/cliniques/' . $newFilename);
                $cliniquePhoto->setUploaded_at(new \DateTime());
                
                $entityManager->persist($cliniquePhoto);
                $entityManager->flush();
                
                error_log('Created new photo entry with ID: ' . $cliniquePhoto->getId_photo());

                return new JsonResponse([
                    'success' => true,
                    'photo_id' => $cliniquePhoto->getId_photo(),
                    'photo_url' => $cliniquePhoto->getPhoto_url()
                ], Response::HTTP_OK);
            } catch (\Exception $e) {
                error_log('Error saving to database: ' . $e->getMessage());
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Erreur lors de l\'enregistrement dans la base de données: ' . $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (\Exception $e) {
            error_log('General error in upload: ' . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    
    #[Route('/test/upload-debug', name: 'app_test_upload_debug', methods: ['GET'])]
    public function testUploadDebug(): Response
    {
        return $this->render('clinique/test_upload.html.twig');
    }
    
    #[Route('/test/handle-test-upload', name: 'app_test_handle_upload', methods: ['POST'])]
    public function handleTestUpload(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            error_log('=== TEST UPLOAD START ===');
            
            
            if (!$request->files->has('files')) {
                error_log('No files in request');
                return new JsonResponse(['success' => false, 'error' => 'No files uploaded'], 400);
            }
            
            $files = $request->files->get('files');
            error_log('Received ' . count($files) . ' files');
            
            
            $cliniqueId = $request->request->get('clinique_id');
            $clinique = null;
            
            if ($cliniqueId) {
                $clinique = $entityManager->getRepository(Clinique::class)->find($cliniqueId);
                error_log('Looking for clinique with ID: ' . $cliniqueId . ', found: ' . ($clinique ? 'yes' : 'no'));
            }
            
            $photoIds = [];
            
            
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                error_log('Processing file: ' . $originalName);
                
                
                $newFilename = uniqid() . '.' . $file->guessExtension();
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/cliniques';
                
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                
                $file->move($uploadDir, $newFilename);
                error_log('File moved to: ' . $uploadDir . '/' . $newFilename);
                
                
                $photo = new Clinique_photos();
                
               
                $lastPhoto = $entityManager->getRepository(Clinique_photos::class)
                    ->findOneBy([], ['id_photo' => 'DESC']);
                $newId = $lastPhoto ? $lastPhoto->getId_photo() + 1 : 1;
                
                $photo->setId_photo($newId);
                $photo->setPhoto_url('/uploads/cliniques/' . $newFilename);
                $photo->setUploaded_at(new \DateTime());
                
                
                if ($clinique) {
                    error_log('Associating with clinique ID: ' . $clinique->getId_clinique());
                    $photo->setCliniqueId($clinique);
                } else {
                    error_log('No clinique provided, photo will have null clinique_id');
                }
                
                $entityManager->persist($photo);
                $photoIds[] = $newId;
            }
            
            
            $entityManager->flush();
            error_log('Saved ' . count($photoIds) . ' photos with IDs: ' . implode(', ', $photoIds));
            
            
            if ($clinique) {
                $connection = $entityManager->getConnection();
                $stmt = $connection->prepare('SELECT id_photo, clinique_id FROM clinique_photos WHERE id_photo IN (' . implode(',', $photoIds) . ')');
                $result = $stmt->executeQuery();
                $rows = $result->fetchAllAssociative();
                error_log('DB state for new photos: ' . print_r($rows, true));
                
                
                $anyNullCliniqueIds = false;
                foreach ($rows as $row) {
                    if (is_null($row['clinique_id'])) {
                        $anyNullCliniqueIds = true;
                        break;
                    }
                }
                
               
                if ($anyNullCliniqueIds) {
                    error_log('Some photos have null clinique_id, trying direct SQL update');
                    try {
                        $sql = "UPDATE clinique_photos SET clinique_id = {$clinique->getId_clinique()} WHERE id_photo IN (" . implode(',', $photoIds) . ")";
                        $connection->executeStatement($sql);
                        
                        
                        $stmt = $connection->prepare('SELECT id_photo, clinique_id FROM clinique_photos WHERE id_photo IN (' . implode(',', $photoIds) . ')');
                        $result = $stmt->executeQuery();
                        $finalRows = $result->fetchAllAssociative();
                        error_log('DB state after direct SQL update: ' . print_r($finalRows, true));
                    } catch (\Exception $e) {
                        error_log('Error in direct SQL update: ' . $e->getMessage());
                    }
                }
            }
            
            error_log('=== TEST UPLOAD END ===');
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'photo_ids' => $photoIds
            ]);
            
        } catch (\Exception $e) {
            error_log('Error in test upload: ' . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Additional debug/test endpoints
    
    #[Route('/test/db-check', name: 'app_test_db_check', methods: ['GET'])]
    public function testDbCheck(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Get all cliniques
            $cliniques = $entityManager->getRepository(Clinique::class)->findAll();
            $cliniquesData = [];
            
            foreach ($cliniques as $clinique) {
                $cliniquesData[] = [
                    'id_clinique' => $clinique->getId_clinique(),
                    'nom' => $clinique->getNom(),
                    'adresse' => $clinique->getAdresse(),
                    'telephone' => $clinique->getTelephone(),
                    'email' => $clinique->getEmail(),
                    'rate' => $clinique->getRate(),
                    'description' => $clinique->getDescription(),
                    'prix' => $clinique->getPrix()
                ];
            }
            
            // Get all photos
            $photos = $entityManager->getRepository(Clinique_photos::class)->findAll();
            $photosData = [];
            
            foreach ($photos as $photo) {
                $photosData[] = [
                    'id_photo' => $photo->getId_photo(),
                    'clinique_id' => $photo->getCliniqueId() ? $photo->getCliniqueId()->getId_clinique() : null,
                    'photo_url' => $photo->getPhoto_url(),
                    'uploaded_at' => $photo->getUploaded_at()->format('Y-m-d H:i:s')
                ];
            }
            
            return new JsonResponse([
                'success' => true,
                'cliniques' => $cliniquesData,
                'photos' => $photosData,
                'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    #[Route('/test/fix-photos/{photoId}/{cliniqueId}', name: 'app_test_fix_photos', methods: ['GET'])]
    public function testFixPhotos(int $photoId, int $cliniqueId, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Find the photo
            $photo = $entityManager->getRepository(Clinique_photos::class)->find($photoId);
            
            if (!$photo) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Photo not found with ID: ' . $photoId
                ], 404);
            }
            
            // Find the clinique
            $clinique = $entityManager->getRepository(Clinique::class)->find($cliniqueId);
            
            if (!$clinique) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Clinique not found with ID: ' . $cliniqueId
                ], 404);
            }
            
            // Get current association for logging
            $oldCliniqueId = $photo->getCliniqueId() ? $photo->getCliniqueId()->getId_clinique() : null;
            
            // Try ORM approach first
            $photo->setCliniqueId($clinique);
            $entityManager->persist($photo);
        $entityManager->flush();

            // Check if ORM update worked
            $updatedPhoto = $entityManager->getRepository(Clinique_photos::class)->find($photoId);
            $newCliniqueId = $updatedPhoto->getCliniqueId() ? $updatedPhoto->getCliniqueId()->getId_clinique() : null;
            
            // If ORM didn't work, try direct SQL
            if ($newCliniqueId !== $cliniqueId) {
                try {
                    $connection = $entityManager->getConnection();
                    $sql = "UPDATE clinique_photos SET clinique_id = :cliniqueId WHERE id_photo = :photoId";
                    $connection->executeStatement($sql, [
                        'cliniqueId' => $cliniqueId,
                        'photoId' => $photoId
                    ]);
                    
                    $newCliniqueId = $cliniqueId;
                } catch (\Exception $e) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Failed to update photo with direct SQL: ' . $e->getMessage()
                    ], 500);
                }
            }
            
            return new JsonResponse([
                'success' => true,
                'photo_id' => $photoId,
                'clinique_id' => $cliniqueId,
                'old_clinique_id' => $oldCliniqueId,
                'new_clinique_id' => $newCliniqueId,
                'message' => 'Photo association updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/front/{id_clinique}', name: 'app_clinique_front_show', methods: ['GET'])]
    public function showFront(Clinique $clinique, SpecialiteRepository $specialiteRepository): Response
    {
        return $this->render('clinique/showFront.html.twig', [
            'clinique' => $clinique,
            'specialites' => $specialiteRepository->findAll(),
        ]);
    }

    #[Route('/qr-code/{id_clinique}', name: 'app_clinique_qr_code', methods: ['GET'])]
    public function generateQrCode(Clinique $clinique, BuilderInterface $qrBuilder): Response
    {
        // Générer le contenu HTML pour le PDF
        $html = $this->renderView('clinique/pdf_template.html.twig', [
            'clinique' => $clinique
        ]);

        // Configurer Dompdf avec des options optimisées pour mobile
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('dpi', 72); // Basse résolution pour fichier plus petit
        $options->set('defaultFont', 'Arial');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Obtenir le contenu du PDF
        $pdfContent = $dompdf->output();

        // Encoder directement en base64
        $base64Pdf = base64_encode($pdfContent);
        
        // Créer une data URI pour le PDF qui sera directement affichée/téléchargée sur mobile
        $dataUri = 'data:application/pdf;filename=clinique.pdf;base64,' . $base64Pdf;
        
        // Générer le QR code avec une taille optimale
        $result = $qrBuilder
            ->data($dataUri)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(1000) // Grande taille pour contenir toutes les données
            ->margin(10)
            ->foregroundColor(new Color(0, 0, 0))
            ->backgroundColor(new Color(255, 255, 255))
            ->build();

        // Retourner le QR code comme image PNG
        $response = new Response($result->getString());
        $response->headers->set('Content-Type', 'image/png');
        
        return $response;
    }

    #[Route('/clinique/pdf/{id_clinique}', name: 'app_clinique_pdf', methods: ['GET'])]
    public function generatePdf(Clinique $clinique): Response
    {
        // Générer le contenu HTML
        $html = $this->renderView('clinique/pdf_template.html.twig', [
            'clinique' => $clinique
        ]);

        // Configurer Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Générer le PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="clinique-'.$clinique->getNom().'.pdf"');

        return $response;
    }
}
