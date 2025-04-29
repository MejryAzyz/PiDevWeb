<?php

namespace App\Controller\Accompagnement;

use App\Entity\Postulation;
use App\Form\PostulationType;
use App\Repository\PostulationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/postulation')]
final class PostulationController extends AbstractController
{
    #[Route('/', name: 'app_postulation_index', methods: ['GET'])]
    public function index(
        Request $request,
        PostulationRepository $postulationRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $postulationRepository->createQueryBuilder('p')
            ->orderBy('p.date_postulation', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 // 6 postulations per page
        );

        return $this->render('postulation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_postulation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postulation = new Postulation();
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postulation->setDatePostulation(new \DateTime());
            $entityManager->persist($postulation);
            $entityManager->flush();

            $this->addFlash('success', 'Postulation created successfully!');
            return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('postulation/new.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_postulation_show', methods: ['GET'])]
    public function show(?Postulation $postulation, LoggerInterface $logger, Request $request): Response
    {
        if (!$postulation) {
            $logger->error('Postulation not found for ID: ' . $request->attributes->get('id'));
            $this->addFlash('error', 'Postulation not found.');
            return $this->redirectToRoute('app_postulation_index');
        }

        return $this->render('postulation/show.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    #[Route('/{id}/demand', name: 'app_postulation_show_demand', methods: ['GET'])]
    public function showDemand(?Postulation $postulation, LoggerInterface $logger, Request $request): Response
    {
        if (!$postulation) {
            $logger->error('Postulation not found for ID: ' . $request->attributes->get('id'));
            $this->addFlash('error', 'Postulation not found.');
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('postulation/showDemand.html.twig', [
            'postulation' => $postulation,
        ]);
    }

    #[Route('/{id_postulation}/edit', name: 'app_postulation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Postulation $postulation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostulationType::class, $postulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Postulation updated successfully!');
            return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('postulation/edit.html.twig', [
            'postulation' => $postulation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_postulation}', name: 'app_postulation_delete', methods: ['POST'])]
    public function delete(Request $request, Postulation $postulation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postulation->getId_Postulation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($postulation);
            $entityManager->flush();
            $this->addFlash('success', 'Postulation deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/accept', name: 'app_postulation_accept', methods: ['GET'])]
    public function accept(
        Postulation $postulation,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger
    ): Response {
        $logger->info('Starting accept action for postulation ID: ' . $postulation->getId_Postulation());

        try {
            // Update postulation status
            $postulation->setStatut('Accepted');
            $logger->info('Set postulation statut to Accepted');

            // Update accompagnateur status
            $accompagnateur = $postulation->getIdAccompagnateur();
            if ($accompagnateur) {
                $accompagnateur->setStatut('Accepted');
                $logger->info('Set accompagnateur statut to Accepted');
            }

            // Update offer etat to inactive
            $offre = $postulation->getIdOffre();
            if ($offre) {
                $offre->setEtat('inactive');
                $logger->info('Set offre etat to inactive');
            }

            // Persist changes
            $entityManager->flush();
            $logger->info('Persisted changes to database');

            // Send acceptance email to accompagnateur
            if ($accompagnateur && $accompagnateur->getEmail()) {
                if (!filter_var($accompagnateur->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $logger->warning('Invalid email address for accompagnateur: ' . $accompagnateur->getEmail());
                    $this->addFlash('warning', 'Postulation accepted, but email could not be sent: Invalid email address.');
                } else {
                    try {
                        $logger->info('Preparing acceptance email', [
                            'to' => $accompagnateur->getEmail(),
                            'from' => $this->getParameter('app.email_sender'),
                            'dsn' => $_ENV['MAILER_DSN'] ?? 'Not set',
                        ]);
                        $emailContent = $this->renderView('emails/postulation_accepted.html.twig', [
                            'username' => $accompagnateur->getUsername() ?? 'Unknown User',
                            'offer_title' => $offre ? $offre->getTitre() : 'Unknown Offer',
                            'postulation_date' => $postulation->getDatePostulation()->format('Y-m-d H:i'),
                        ]);

                        $email = (new Email())
                            ->from($this->getParameter('app.email_sender'))
                            ->to($accompagnateur->getEmail())
                            ->subject('Your Postulation Has Been Accepted!')
                            ->text('This is a plain text version of the email.')
                            ->html($emailContent);

                        $logger->info('Sending acceptance email to ' . $accompagnateur->getEmail());
                        $mailer->send($email);
                        $logger->info('Acceptance email sent to ' . $accompagnateur->getEmail());
                        $this->addFlash('success', 'Postulation accepted and email sent successfully.');
                    } catch (TransportExceptionInterface $e) {
                        $logger->error('Failed to send acceptance email: ' . $e->getMessage());
                        $this->addFlash('warning', 'Postulation accepted, but email could not be sent: ' . $e->getMessage());
                    }
                }
            } else {
                $logger->warning('No email address found for accompagnateur on postulation ' . $postulation->getId_Postulation());
                $this->addFlash('warning', 'Postulation accepted, but no email address found for the applicant.');
            }
        } catch (\Exception $e) {
            $logger->error('Error in accept action: ' . $e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', 'Failed to accept postulation: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/reject', name: 'app_postulation_reject', methods: ['GET'])]
    public function reject(
        Postulation $postulation,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger
    ): Response {
        $logger->info('Starting reject action for postulation ID: ' . $postulation->getId_Postulation());

        try {
            // Update postulation status
            $postulation->setStatut('Rejected');
            $logger->info('Set postulation statut to Rejected');

            // Update accompagnateur status
            $accompagnateur = $postulation->getIdAccompagnateur();
            if ($accompagnateur) {
                $accompagnateur->setStatut('Rejected');
                $logger->info('Set accompagnateur statut to Rejected');
            }

            // Persist changes
            $entityManager->flush();
            $logger->info('Persisted changes to database');

            // Send rejection email to accompagnateur
            if ($accompagnateur && $accompagnateur->getEmail()) {
                if (!filter_var($accompagnateur->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $logger->warning('Invalid email address for accompagnateur: ' . $accompagnateur->getEmail());
                    $this->addFlash('warning', 'Postulation rejected, but email could not be sent: Invalid email address.');
                } else {
                    try {
                        $logger->info('Preparing rejection email', [
                            'to' => $accompagnateur->getEmail(),
                            'from' => $this->getParameter('app.email_sender'),
                            'dsn' => $_ENV['MAILER_DSN'] ?? 'Not set',
                        ]);
                        $emailContent = $this->renderView('emails/postulation_rejected.html.twig', [
                            'username' => $accompagnateur->getUsername() ?? 'Unknown User',
                            'offer_title' => $postulation->getIdOffre() ? $postulation->getIdOffre()->getTitre() : 'Unknown Offer',
                            'postulation_date' => $postulation->getDatePostulation()->format('Y-m-d H:i'),
                        ]);

                        $email = (new Email())
                            ->from($this->getParameter('app.email_sender'))
                            ->to($accompagnateur->getEmail())
                            ->subject('Your Postulation Has Been Rejected')
                            ->text('This is a plain text version of the email.')
                            ->html($emailContent);

                        $logger->info('Sending rejection email to ' . $accompagnateur->getEmail());
                        $mailer->send($email);
                        $logger->info('Rejection email sent to ' . $accompagnateur->getEmail());
                        $this->addFlash('success', 'Postulation rejected and email sent successfully.');
                    } catch (TransportExceptionInterface $e) {
                        $logger->error('Failed to send rejection email: ' . $e->getMessage());
                        $this->addFlash('warning', 'Postulation rejected, but email could not be sent: ' . $e->getMessage());
                    }
                }
            } else {
                $logger->warning('No email address found for accompagnateur on postulation ' . $postulation->getId_Postulation());
                $this->addFlash('warning', 'Postulation rejected, but no email address found for the applicant.');
            }
        } catch (\Exception $e) {
            $logger->error('Error in reject action: ' . $e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', 'Failed to reject postulation: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_postulation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/stats', name: 'app_postulation_stats', methods: ['GET'])]
    public function stats(PostulationRepository $postulationRepository): Response
    {
        $stats = $postulationRepository->findPostulationStats();

        return $this->render('postulation/stats.html.twig', [
            'postulation_stats' => $stats,
        ]);
    }
}
