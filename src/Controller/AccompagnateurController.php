<?php

namespace App\Controller;

use App\Entity\Accompagnateur;
use App\Form\AccompagnateurType;
use App\Repository\AccompagnateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/accompagnateur')]
class AccompagnateurController extends AbstractController
{
    #[Route('/', name: 'app_accompagnateur_index', methods: ['GET'])]
    public function index(
        Request $request,
        AccompagnateurRepository $accompagnateurRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $accompagnateurRepository->createQueryBuilder('a')
            ->orderBy('a.date_inscription', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 // 6 accompagnateurs per page
        );

        return $this->render('accompagnateur/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_accompagnateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccompagnateurRepository $accompagnateurRepository, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $accompagnateur = new Accompagnateur();
        $form = $this->createForm(AccompagnateurType::class, $accompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password hashing
            $plainPassword = $form->get('password_hash')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($accompagnateur, $plainPassword);
                $accompagnateur->setPasswordHash($hashedPassword);
            }

            // Handle file uploads
            $cvFile = $form->get('fichier_cv')->getData();
            $photoFile = $form->get('photo_profil')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                    $accompagnateur->setFichier_Cv($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload CV file.');
                }
            }

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                    $accompagnateur->setPhoto_Profil($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload profile photo.');
                }
            }

            // Set default values if needed
            $accompagnateur->setDate_Inscription(new \DateTime());
            $accompagnateur->setDate_Recrutement(new \DateTime());
            $accompagnateur->setStatut("Pending");


            $accompagnateurRepository->save($accompagnateur, true);

            $this->addFlash('success', 'Accompagnateur created successfully.');
            return $this->redirectToRoute('app_accompagnateur_index');
        }

        return $this->render('accompagnateur/new.html.twig', [
            'accompagnateur' => $accompagnateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_accompagnateur_show', methods: ['GET'])]
    public function show(Accompagnateur $accompagnateur): Response
    {
        return $this->render('accompagnateur/show.html.twig', [
            'accompagnateur' => $accompagnateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accompagnateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accompagnateur $accompagnateur, AccompagnateurRepository $accompagnateurRepository, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AccompagnateurType::class, $accompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password update
            $plainPassword = $form->get('password_hash')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($accompagnateur, $plainPassword);
                $accompagnateur->setPasswordHash($hashedPassword);
            }

            // Handle file uploads
            $cvFile = $form->get('fichier_cv')->getData();
            $photoFile = $form->get('photo_profil')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                    $accompagnateur->setFichier_Cv($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload CV file.');
                }
            }

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                    $accompagnateur->setPhoto_Profil($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload profile photo.');
                }
            }

            $accompagnateurRepository->save($accompagnateur, true);

            $this->addFlash('success', 'Accompagnateur updated successfully.');
            return $this->redirectToRoute('app_accompagnateur_index');
        }

        return $this->render('accompagnateur/edit.html.twig', [
            'accompagnateur' => $accompagnateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_accompagnateur_delete', methods: ['POST'])]
    public function delete(Request $request, Accompagnateur $accompagnateur, AccompagnateurRepository $accompagnateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accompagnateur->getId_Accompagnateur(), $request->request->get('_token'))) {
            $accompagnateurRepository->remove($accompagnateur, true);
            $this->addFlash('success', 'Accompagnateur deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_accompagnateur_index');
    }
}