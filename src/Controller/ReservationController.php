<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function index(ReservationRepository $reservationRepository): Response
    {
        // Get groups of reservations based on non-null foreign keys
        $clinicReservations = $reservationRepository->findByClinic();
        $transportReservations = $reservationRepository->findByTransport();
        $hebergementReservations = $reservationRepository->findByHebergement();

        return $this->render('reservation/index.html.twig', [
            'clinic_reservations' => $clinicReservations,
            'transport_reservations' => $transportReservations,
            'hebergement_reservations' => $hebergementReservations,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }



    #[Route('/{id_reservation}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
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
