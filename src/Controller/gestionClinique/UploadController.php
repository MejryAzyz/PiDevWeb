<?php

namespace App\Controller\gestionClinique;

use App\Entity\Clinique_photos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{
    #[Route('/upload/clinique-photo', name: 'app_upload_clinique_photo', methods: ['POST'])]
    public function uploadCliniquePhoto(Request $request, EntityManagerInterface $entityManager): JsonResponse
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

            // Move the file
            $file->move($uploadDir, $newFilename);
            error_log('File moved successfully to: ' . $uploadDir . '/' . $newFilename);

            // Create new Clinique_photos entry
            $cliniquePhoto = new Clinique_photos();
            
            // Generate new ID
            $lastPhoto = $entityManager->getRepository(Clinique_photos::class)
                ->findOneBy([], ['id_photo' => 'DESC']);
            $newId = $lastPhoto ? $lastPhoto->getId_photo() + 1 : 1;
            
            $cliniquePhoto->setId_photo($newId);
            $cliniquePhoto->setPhoto_url('/uploads/cliniques/' . $newFilename);
            $cliniquePhoto->setUploaded_at(new \DateTime());
            // Explicitly set clinique_id to null until it's associated with a clinique
            $cliniquePhoto->setCliniqueId(null);
            
            $entityManager->persist($cliniquePhoto);
            $entityManager->flush();
            
            error_log('Created new photo entry with ID: ' . $cliniquePhoto->getId_photo());

            return new JsonResponse([
                'success' => true,
                'photo_id' => $cliniquePhoto->getId_photo(),
                'photo_url' => $cliniquePhoto->getPhoto_url()
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            error_log('Error in upload: ' . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 