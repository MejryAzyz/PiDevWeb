<?php

namespace App\Controller;

use App\Entity\Clinique;
use App\Entity\Hebergement;
use App\Entity\Reservation;
use App\Entity\Transport;
use App\Form\ReservationType;
use App\Form\HebergementReservationType;
use App\Form\ReservationTransportEditType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('/clinic-reservation/delete/{id}', name: 'clinic_reservation_delete', methods: ['POST'])]
    public function deletef(Request $request, Reservation  $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getIdReservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation annulée avec succès.');
        }

        return $this->redirectToRoute('mesReservation');
    }

    #[Route('/mesReservation', name: 'mesReservation', methods: ['GET'])]
    public function mesReservation(ReservationRepository $reservationRepository): Response
    {
        // Get groups of reservations based on non-null foreign keys
        $clinicReservations = $reservationRepository->findByClinic();
        $transportReservations = $reservationRepository->findByTransport();
        $hebergementReservations = $reservationRepository->findByHebergement();

        return $this->render('reservation/front_index.html.twig', [
            'clinic_reservations' => $clinicReservations,
            'transport_reservations' => $transportReservations,
            'hebergement_reservations' => $hebergementReservations,
        ]);
    }

    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Get groups of reservations based on non-null foreign keys
        $clinicReservations = $reservationRepository->findByClinic();
        $clinicReservationsp = $paginator->paginate(
            $clinicReservations, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        $transportReservations = $reservationRepository->findByTransport();
        $transportReservationsp = $paginator->paginate(
            $transportReservations, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        $hebergementReservations = $reservationRepository->findByHebergement();
        $hebergementReservationsp = $paginator->paginate(
            $hebergementReservations, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('reservation/index.html.twig', [
            'clinic_reservations' => $clinicReservationsp,
            'transport_reservations' => $transportReservationsp,
            'hebergement_reservations' => $hebergementReservationsp,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        if ($_GET['type'] == "hebergement") {
            $form = $this->createForm(HebergementReservationType::class, $reservation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $reservation->setId_patient(1);
                $Hebergement = $entityManager->getRepository(Hebergement::class)->find($_GET['id']);
                $reservation->setid_hebergement($Hebergement);
                $reservation->setStatut("in progress");
                $reservation->setDate_reservation(new \DateTime()); // Current date and time
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('mesReservation', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('reservation/updateresh.htm.twig', [
                'reservation' => $reservation,
                'form' => $form,
            ]);
        } else if ($_GET['type'] == "transport") {
            $form = $this->createForm(ReservationTransportEditType::class, $reservation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $reservation->setId_patient(1);
                $transport = $entityManager->getRepository(Transport::class)->find($_GET['id']);
                $reservation->setid_transport($transport);
                $reservation->setStatut("in progress");
                $reservation->setDate_reservation(new \DateTime()); // Current date and time
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('mesReservation', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('reservation/updaterest.html.twig', [
                'reservation' => $reservation,
                'form' => $form,
            ]);
        } else if ($_GET['type'] == "clinique") {
            $reservation->setId_patient(1);
            $c = $entityManager->getRepository(Clinique::class)->find($_GET['id']);
            $reservation->setIdClinique($c);
            $reservation->setStatut("in progress");
            $reservation->setDate_reservation(new \DateTime()); // Current date and time
            $entityManager->persist($reservation);
            $entityManager->flush();
            return $this->redirectToRoute('mesReservation', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reservation/updateresh.htm.twig', [
            'reservation' => $reservation,
        ]);
    }


    #[Route('/edit_transport/{id}', name: 'transport_reservation_edit')]
    public function editTransport(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationTransportEditType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Réservation mise à jour avec succès.');
            return $this->redirectToRoute('mesReservation'); // Replace with your reservation listing route
        }

        return $this->render('reservation/updaterest.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit_hebergement/{id}', name: 'hebergement_reservation_edit')]
    public function editHebergement(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Réservation hébergement mise à jour avec succès.');
            return $this->redirectToRoute('mesReservation'); // Or your route name
        }

        return $this->render('reservation/updateresh.htm.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_reservation}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Create the form and handle the request
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Ensure the reservation has an ID
            $entityManager->flush();

            $id_reservation = $reservation->getId_reservation();  // Access the correct getter

            // Now check the statut field and perform the correct redirect
            if ($reservation->getStatut() === 'accepted') {
                $email = (new Email())
                    ->from('beyaabid876@gmail.com')
                    ->to('beyaabid876@gmail.com')
                    ->subject('Confirmation de votre réservation chez MedTravel')
                    ->html('
                        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #f9f9f9;">
                            <h2 style="color: #2a9d8f;">Bonjour,</h2>
                            <p style="font-size: 16px; color: #333;">
                                Nous avons le plaisir de vous informer que votre <strong>réservation</strong> a été <strong>acceptée</strong> par un administrateur.
                            </p>
                            <p style="font-size: 16px; color: #333;">
                                Toute l’équipe de <strong>MedTravel</strong> vous remercie pour votre confiance.
                            </p>
                            <div style="margin-top: 30px; text-align: center;">
                                <a href="http://127.0.0.1:8000/reservation/mesReservation" style="background-color: #2a9d8f; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Visitez notre site</a>
                            </div>
                            <p style="font-size: 12px; color: #888; margin-top: 30px; text-align: center;">
                                Ce message a été généré automatiquement. Merci de ne pas y répondre.
                            </p>
                        </div>
                    ');

                $mailer->send($email);
                return $this->redirectToRoute('app_paiement_new', ['id_reservation' => $id_reservation]);
            }

            if ($reservation->getStatut() === 'rejected') {
                return $this->redirectToRoute('app_reservation_index');
            }

            // Fallback redirect if statut is neither Accepted nor Rejected
            return $this->redirectToRoute('app_reservation_index');
        }

        // Render the form
        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id_reservation}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId_reservation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
