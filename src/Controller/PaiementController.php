<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Entity\Reservation;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paiement')]
final class PaiementController extends AbstractController
{
    #[Route(name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        return $this->render('reservation/paiements.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);
        $idr = $_GET['id_reservation'];
        if ($form->isSubmitted() && $form->isValid()) {
            $paiement->setId_reservation($entityManager->getRepository('App\Entity\Reservation')->find($idr));
            $paiement->setDate_paiement(new \DateTime());
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{x}', name: 'paiement', methods: ['GET'])]
    public function paiement(int $x, PaiementRepository $paiementRepository): Response
    {
        // Use the custom repository method to get the paiements by id_reservation
        $paiements = $paiementRepository->findAllByIdreservation($x);

        // If no paiements are found, handle it gracefully
        if (!$paiements) {
            throw $this->createNotFoundException('No Paiement found for this reservation');
        }
        // Pass the paiements to the template
        return $this->render('paiement/show.html.twig', [
            'paiements' => $paiements,  // Make sure the name is correct (paiements, plural)
        ]);
    }

    #[Route('/stripe/payment/{id}', name: 'stripe_payment')]
    public function stripePayment(int $id, Request $request, Reservation $reservation, PaiementRepository $paiementRepository): Response
    {
        // Optional: Check reservation status
        if ($reservation->getStatut() !== 'accepted') {
            $this->addFlash('warning', 'Seules les réservations acceptées peuvent être payées.');
            return $this->redirectToRoute('mesReservation'); // Adjust if needed
        }

        $paiements = $paiementRepository->findAllByIdreservation($id);

        if (empty($paiements)) {
            $this->addFlash('error', 'Aucun paiement trouvé pour cette réservation.');
            return $this->redirectToRoute('mesReservation');
        }

        // Assuming you want the amount of the first payment (adjust if necessary)
        $amount = $paiements[0]->getMontant(); // Use getter properly
        $id = $paiements[0]->getId_paiement(); // Use getter properly
        return $this->render('reservation/mock_stripe.html.twig', [
            'reservation' => $reservation,
            'amount' => $amount,
            'id' => $id,
        ]);
    }

    #[Route('/stripe/submit/{id}', name: 'stripe_payment_submit')]
    public function handleStripePayment(
        int $id,
        PaiementRepository $paiementRepository,
        EntityManagerInterface $em
    ): Response {
        // Get related payment
        $paiement = $paiementRepository->find($id);

        if (!$paiement) {
            $this->addFlash('error', 'Paiement introuvable pour cette réservation.');
            return $this->redirectToRoute('mesReservation');
        }

        // Simulate successful payment
        $paiement->setMethode('carte bancaire');
        $em->persist($paiement);
        $em->flush();

        $this->addFlash('success', 'Paiement enregistré avec succès !');
        return $this->redirectToRoute('app_paiement_index'); // Replace with your actual route
    }

    #[Route('/{id_paiement}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }


    #[Route('/{id_paiement}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // After successful edit, redirect back to the 'app_paiement_show' route with the paiement's ID
            return $this->redirectToRoute('paiement', [
                'x' => $paiement->getId_reservation()->getId_reservation(),
            ]);
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{idres}', name: 'cancel', methods: ['POST'])]
    public function cancel(
        Request $request,
        int $idres,
        EntityManagerInterface $entityManager,
        PaiementRepository $paiementRepository
    ): Response {
        // Find the reservation
        $reservation = $entityManager->getRepository(Reservation::class)->find($idres);

        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }

        // Update status
        $reservation->setStatut('rejected');

        // Get paiements
        $paiements = $paiementRepository->findAllByIdreservation($idres);

        // Remove all associated paiements (if any), checking CSRF token
        foreach ($paiements as $paiement) {
            $entityManager->remove($paiement);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id_paiement}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $paiement->getId_paiement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
