<?php

namespace App\Controller\Transport;

use App\Entity\Transport;
use App\Form\TransportType;
use App\Repository\TransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/transport')]
final class TransportController extends AbstractController
{
    #[Route('/admin', name: 'app_transport_index', methods: ['GET'])]
    public function index(TransportRepository $transportRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $transportRepository->createQueryBuilder('t')
            ->orderBy('t.type', 'ASC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // Nombre d'éléments par page
        );

        // Calculate dynamic stats
        $totalCapacity = array_sum(array_map(fn($t) => $t->getCapacite(), $pagination->getItems()));
        $tariffs = array_map(fn($t) => $t->getTarif(), $pagination->getItems());
        $averageTariff = count($tariffs) > 0 ? array_sum($tariffs) / count($tariffs) : 0;
        $totalTransports = $pagination->getTotalItemCount();

        return $this->render('transport/index.html.twig', [
            'transports' => $pagination,
            'total_capacity' => $totalCapacity,
            'average_tariff' => $averageTariff,
            'total_transports' => $totalTransports,
        ]);
    }

    #[Route('/admin/new', name: 'app_transport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transport = new Transport();
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transport);
            $entityManager->flush();
            return $this->redirectToRoute('app_transport_index');
        }

        return $this->render('transport/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{id_transport}', name: 'app_transport_show', methods: ['GET'])]
    public function show(Transport $transport): Response
    {
        return $this->render('transport/show.html.twig', [
            'transport' => $transport,
        ]);
    }

    #[Route('/admin/{id_transport}/edit', name: 'app_transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transport/edit.html.twig', [
            'transport' => $transport,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id_transport}', name: 'app_transport_delete', methods: ['POST'])]
    public function delete(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transport->getId_transport(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/export/pdf', name: 'app_transport_export_pdf', methods: ['GET'])]
    public function exportPdf(TransportRepository $transportRepository): Response
    {
        // Récupérer tous les transports
        $transports = $transportRepository->findAll();
        
        // Créer le contenu HTML pour le PDF
        $html = $this->renderView('transport/pdf.html.twig', [
            'transports' => $transports,
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
        $filename = 'transports_' . date('Y-m-d_H-i-s') . '.pdf';
        
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

    #[Route('/front', name: 'app_transport_front_index', methods: ['GET'])]
    public function frontIndex(Request $request, TransportRepository $transportRepository): Response
    {
        $query = $transportRepository->createQueryBuilder('t')
            ->orderBy('t.id_transport', 'DESC')
            ->getQuery();

        $page = $request->query->getInt('page', 1);
        $limit = 6; // Nombre d'éléments par page

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $limit);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $this->render('transport/front_index.html.twig', [
            'transports' => $paginator,
            'currentPage' => $page,
            'pagesCount' => $pagesCount,
        ]);
    }

    #[Route('/front/{id_transport}', name: 'app_transport_front_show', methods: ['GET'])]
    public function frontShow(Transport $transport): Response
    {
        return $this->render('transport/front_show.html.twig', [
            'transport' => $transport,
        ]);
    }
}
