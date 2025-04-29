<?php

namespace App\Controller;

use App\Entity\Offreemploi;
use App\Entity\Accompagnateur;
use App\Entity\Postulation;
use App\Form\AccompagnateurType;
use App\Repository\OffreemploiRepository;
use App\Repository\AccompagnateurRepository;
use App\Repository\PostulationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/offres')]
class OffreemploiController extends AbstractController
{
    #[Route('', name: 'app_offreemploi_front_index', methods: ['GET'])]
    public function index(
        OffreemploiRepository $offreemploiRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $qb = $offreemploiRepository->createQueryBuilder('o')
            ->where('o.etat = :etat')
            ->setParameter('etat', 'active');

        // Job type filter
        $jobTypes = $request->query->all()['job_type'] ?? [];
        if (!empty($jobTypes) && is_array($jobTypes)) {
            $qb->andWhere('o.typeposte IN (:job_types)')
                ->setParameter('job_types', $jobTypes);
        }

        // Contract type filter
        $contractTypes = $request->query->all()['contract_type'] ?? [];
        if (!empty($contractTypes) && is_array($contractTypes)) {
            $qb->andWhere('o.typecontrat IN (:contract_types)')
                ->setParameter('contract_types', $contractTypes);
        }

        // Keywords filter
        $keywords = trim($request->query->get('keywords', ''));
        if ($keywords !== '') {
            $qb->andWhere('o.titre LIKE :keywords OR o.description LIKE :keywords')
                ->setParameter('keywords', '%' . $keywords . '%');
        }

        // Sorting
        $sort = $request->query->get('sort', 'date');
        if ($sort === 'relevance' && $keywords !== '') {
            $qb->orderBy('o.titre', 'ASC');
        } elseif ($sort === 'recommended') {
            $qb->orderBy('o.datepublication', 'DESC');
        } else {
            $qb->orderBy('o.datepublication', 'DESC');
        }

        $query = $qb->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 // 6 offers per page
        );

        return $this->render('front/offreemploi/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/offre/{id}', name: 'app_offreemploi_front_show', methods: ['GET'])]
    public function show(Offreemploi $offre): Response
    {
        return $this->render('front/offreemploi/show.html.twig', [
            'offre' => $offre,
        ]);
    }


    #[Route('/offre/{id}/postuler', name: 'app_offreemploi_postuler', methods: ['GET', 'POST'])]
    public function postuler(
        Request $request,
        Offreemploi $offreemploi,
        AccompagnateurRepository $accompagnateurRepository,
        PostulationRepository $postulationRepository,
        PasswordHasherFactoryInterface $passwordHasherFactory,
        SluggerInterface $slugger,
        LoggerInterface $logger
    ): Response {
        $accompagnateur = new Accompagnateur();
        $form = $this->createForm(AccompagnateurType::class, $accompagnateur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    // Hash password
                    $plainPassword = $form->get('password_hash')->getData();
                    $hasher = $passwordHasherFactory->getPasswordHasher(Accompagnateur::class);
                    $hashedPassword = $hasher->hash($plainPassword);
                    $accompagnateur->setPasswordHash($hashedPassword);

                    // Handle CV file upload
                    $cvFile = $form->get('fichier_cv')->getData();
                    if ($cvFile) {
                        $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
                        try {
                            $cvFile->move($this->getParameter('cv_directory'), $newFilename);
                            $accompagnateur->setFichier_Cv($newFilename);
                        } catch (FileException $e) {
                            $logger->error('Failed to upload CV file: ' . $e->getMessage());
                            $this->addFlash('error', 'Failed to upload CV file: ' . $e->getMessage());
                            return $this->render('front/offreemploi/postuler.html.twig', [
                                'offre' => $offreemploi,
                                'form' => $form->createView(),
                            ]);
                        }
                    }

                    // Handle photo file upload
                    $photoFile = $form->get('photo_profil')->getData();
                    if ($photoFile) {
                        $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
                        try {
                            $photoFile->move($this->getParameter('photo_directory'), $newFilename);
                            $accompagnateur->setPhoto_Profil($newFilename);
                        } catch (FileException $e) {
                            $logger->error('Failed to upload photo file: ' . $e->getMessage());
                            $this->addFlash('error', 'Failed to upload profile photo: ' . $e->getMessage());
                            return $this->render('front/offreemploi/postuler.html.twig', [
                                'offre' => $offreemploi,
                                'form' => $form->createView(),
                            ]);
                        }
                    }

                    // Set defaults
                    $accompagnateur->setStatut('Pending');
                    $accompagnateur->setDate_Inscription(new \DateTime());
                    $accompagnateur->setDate_Recrutement(new \DateTime());

                    // Save Accompagnateur
                    $logger->info('Saving Accompagnateur: ' . $accompagnateur->getEmail());
                    $accompagnateurRepository->save($accompagnateur, true);

                    // Create and save Postulation
                    $postulation = new Postulation();
                    $postulation->setIdAccompagnateur($accompagnateur);
                    $postulation->setIdOffre($offreemploi);
                    $postulation->setStatut('Pending');
                    $postulation->setDatePostulation(new \DateTime());

                    $logger->info('Saving Postulation for offer: ' . $offreemploi->getTitre());
                    $postulationRepository->save($postulation, true);

                    $this->addFlash('success', 'Votre candidature a été soumise avec succès !');
                    return $this->redirectToRoute('app_offreemploi_front_index');
                } catch (\Exception $e) {
                    $logger->error('Error saving application: ' . $e->getMessage(), ['exception' => $e]);
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre candidature : ' . $e->getMessage());
                }
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $logger->error('Form validation error: ' . $error->getMessage());
                    $this->addFlash('error', 'Form error: ' . $error->getMessage());
                }
            }
        }

        return $this->render('front/offreemploi/postuler.html.twig', [
            'offre' => $offreemploi,
            'form' => $form->createView(),
        ]);
    }
}
