<?php

namespace App\Controller\gestionClinique;

use App\Entity\Clinique;
use App\Entity\Clinique_photos;
use App\Form\CliniqueType;
use App\Repository\CliniqueRepository;
use App\Repository\DocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
            // Si une photo a été téléchargée, associez-la à la clinique
        $photoIds = $request->get('photo_ids'); // Récupérer les ID de la photo envoyée
        if ($photoIds) {
            $photoIds = json_decode($photoIds); // Décoder le JSON
            foreach ($photoIds as $photoId) {
                $photo = $entityManager->getRepository(Clinique_photos::class)->find($photoId);
                if ($photo) {
                    // Ajouter la photo à la clinique (si la relation est définie)
                    $clinique->addCliniquePhoto($photo); // Assurez-vous que vous avez une méthode addPhoto dans votre entité Clinique
                }
            }
        }

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

    // Nouvelle route pour le front-office (client)
    // #[Route('/front', name: 'app_clinique_front', methods: ['GET'])]
    // public function front(CliniqueRepository $cliniqueRepository): Response
    // {
    //     return $this->render('clinique/indexFront.html.twig', [
    //         'cliniques' => $cliniqueRepository->findAll(),
    //     ]);
    // }

     // ➕ Méthode front pour afficher toutes les cliniques
     #[Route('/front/liste', name: 'app_clinique_front_index', methods: ['GET'])]
     public function indexFront(CliniqueRepository $cliniqueRepository): Response
     {
         return $this->render('clinique/indexFront.html.twig', [
             'cliniques' => $cliniqueRepository->findAll(),
         ]);
     }
    

     //upload image
    //  #[Route('/upload-photo', name: 'app_clinique_upload_photo', methods: ['POST'])]
    // public function uploadPhoto(Request $request, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $file = $request->files->get('file');
    //     if (!$file) {
    //         return new JsonResponse(['error' => 'Aucun fichier téléversé'], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Validation du fichier
    //     $allowedMimeTypes = ['image/jpeg', 'image/png'];
    //     if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
    //         return new JsonResponse(['error' => 'Type de fichier non autorisé (JPG ou PNG uniquement)'], Response::HTTP_BAD_REQUEST);
    //     }
    //     if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
    //         return new JsonResponse(['error' => 'Fichier trop volumineux (max 5MB)'], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Générer un nom unique et déplacer le fichier dans public/uploads
    //     $newFilename = uniqid() . '.' . $file->guessExtension();
    //     $uploadDir = $this->getParameter('UPLOADS_DIRECTORY');
    //     try {
    //         $file->move($uploadDir, $newFilename);
    //     } catch (FileException $e) {
    //         return new JsonResponse(['error' => 'Erreur lors de l’upload : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }

    //     // Créer une entrée dans CliniquePhoto
    //     $cliniquePhoto = new Clinique_photos();
    //     $cliniquePhoto->setPhotoUrl('/uploads/' . $newFilename);
    //     $cliniquePhoto->setUploadedAt(new \DateTime());
    //     $entityManager->persist($cliniquePhoto);
    //     $entityManager->flush();

    //     // Retourner l'ID de la photo pour l'associer plus tard
    //     return new JsonResponse(['success' => true, 'photo_id' => $cliniquePhoto->getIdPhoto()]);
    // }
}
