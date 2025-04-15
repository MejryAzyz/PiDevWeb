<?php

namespace App\Controller\Hebergement;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/hebergement')]
final class HebergementController extends AbstractController
{
    #[Route(name: 'app_hebergement_index', methods: ['GET'])]
    public function index(HebergementRepository $hebergementRepository): Response
    {
        $hebergements = $hebergementRepository->findAll();
        
        // Aggregate data for charts
        $totalCapacity = array_sum(array_map(fn($h) => $h->getCapacite(), $hebergements));
        $tariffs = array_map(fn($h) => $h->getTarifNuit(), $hebergements);
        $capacityData = array_map(fn($h) => [$h->getNom(), $h->getCapacite()], $hebergements);
        
        // Calculate average tariff
        $averageTariff = count($tariffs) > 0 ? array_sum($tariffs) / count($tariffs) : 0;
    
        return $this->render('hebergement/index.html.twig', [
            'hebergements' => $hebergements,
            'total_capacity' => $totalCapacity,
            'tariffs' => json_encode($tariffs),
            'capacity_data' => json_encode($capacityData),
            'average_tariff' => $averageTariff,
        ]);
    }

    #[Route('/front', name: 'app_hebergement_front_index', methods: ['GET'])]
    public function frontIndex(
        Request $request,
        HebergementRepository $hebergementRepository
    ): Response {
        // Récupérer les paramètres de filtrage
        $search = $request->query->get('search', '');
        
        // Récupérer et convertir les valeurs de prix
        $minPrice = $request->query->has('min_price') ? (float) $request->query->get('min_price') : 0;
        $maxPrice = $request->query->has('max_price') ? (float) $request->query->get('max_price') : 1000;
        
        // Récupérer et convertir les valeurs de capacité
        $capacityMin = $request->query->has('capacity_min') ? (int) $request->query->get('capacity_min') : 0;
        $capacityMax = $request->query->has('capacity_max') ? (int) $request->query->get('capacity_max') : 1000;
        
        $nation = $request->query->get('nation', '');
        $sortBy = $request->query->get('sort_by', 'recommended');
        $sortOrder = $request->query->get('sort_order', 'asc');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 5;
        
        // Log pour déboguer
        dump([
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'capacityMin' => $capacityMin,
            'capacityMax' => $capacityMax,
            'nation' => $nation
        ]);
        
        // Récupérer les hébergements filtrés
        $hebergements = $hebergementRepository->findFilteredHebergements(
            $search,
            $minPrice,
            $maxPrice,
            $capacityMin,
            $capacityMax,
            $nation,
            $sortBy,
            $sortOrder,
            $page,
            $limit
        );
        
        // Calculer la pagination
        $totalHebergements = $hebergementRepository->countFilteredHebergements(
            $search,
            $minPrice,
            $maxPrice,
            $capacityMin,
            $capacityMax,
            $nation
        );
        $totalPages = ceil($totalHebergements / $limit);
        
        // Récupérer les capacités uniques pour le filtre
        $capacities = $hebergementRepository->findUniqueCapacities();
        
        // Récupérer la plage de prix min et max
        $priceRange = $hebergementRepository->findPriceRange();
        
        // Récupérer les nations uniques pour le filtre
        $nations = $hebergementRepository->findUniqueNations();
        
        return $this->render('hebergement/front_index.html.twig', [
            'hebergements' => $hebergements,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'search' => $search,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'capacity_min' => $capacityMin,
            'capacity_max' => $capacityMax,
            'nation' => $nation,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'capacities' => $capacities,
            'price_range' => $priceRange,
            'nations' => $nations,
        ]);
    }

    #[Route('/front/{id_hebergement}', name: 'app_hebergement_front_show', methods: ['GET'])]
    public function frontShow(Hebergement $hebergement): Response
    {
        return $this->render('hebergement/front_show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/new', name: 'app_hebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // The image_url is already set on the entity by the form, no need to fetch it manually
            // $imageUrl = $request->get('hebergement')['image_url']; // Remove this
            // $hebergement->setImageUrl($imageUrl); // Remove this

            $entityManager->persist($hebergement);
            $entityManager->flush();

            $this->addFlash('success', 'Hebergement created successfully!');

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        // Add a flash message for invalid form submission (optional)
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors in the form.');
        }

        return $this->render('hebergement/new.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(), // Use createView() for clarity
        ]);
    }

    #[Route('/{id_hebergement}', name: 'app_hebergement_show', methods: ['GET'])]
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('hebergement/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/{id_hebergement}/edit', name: 'app_hebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Hebergement updated successfully!');

            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors in the form.');
        }

        return $this->render('hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_hebergement}', name: 'app_hebergement_delete', methods: ['POST'])]
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hebergement->getIdHebergement(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();

            $this->addFlash('success', 'Hebergement deleted successfully!');
        }

        return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/pdf', name: 'app_hebergement_export_pdf', methods: ['GET'])]
    public function exportPdf(HebergementRepository $hebergementRepository): Response
    {
        // Récupérer tous les hébergements
        $hebergements = $hebergementRepository->findAll();
        
        // Créer le contenu HTML pour le PDF
        $html = $this->renderView('hebergement/pdf.html.twig', [
            'hebergements' => $hebergements,
            'date' => new \DateTime(),
        ]);
        
        // Configurer Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Créer une instance de Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        // Définir le format du papier et l'orientation
        $dompdf->setPaper('A4', 'portrait');
        
        // Rendre le PDF
        $dompdf->render();
        
        // Générer le nom du fichier
        $filename = 'hebergements_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Retourner le PDF comme réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}