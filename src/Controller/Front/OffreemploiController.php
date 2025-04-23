<?php

namespace App\Controller\Front;

use App\Entity\Offreemploi;
use App\Repository\OffreemploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jobs')]
class OffreemploiController extends AbstractController
{
    #[Route('/', name: 'app_front_offreemploi_index', methods: ['GET'])]
    public function index(OffreemploiRepository $offreemploiRepository): Response
    {
        return $this->render('front/offreemploi/index.html.twig', [
            'offreemplois' => $offreemploiRepository->findBy(['etat' => 'active']),
        ]);
    }

    #[Route('/{id}', name: 'app_front_offreemploi_show', methods: ['GET'])]
    public function show(Offreemploi $offreemploi, OffreemploiRepository $offreemploiRepository): Response
    {
        if ($offreemploi->getEtat() !== 'active') {
            throw $this->createNotFoundException('This job offer is not available.');
        }

        // Get similar job offers (same type or contract type, excluding current one)
        $similarJobs = $offreemploiRepository->findBy(
            [
                'etat' => 'active',
                'typeposte' => $offreemploi->getTypeposte(),
            ],
            ['datepublication' => 'DESC'],
            4
        );

        // Remove current job from similar jobs if present
        $similarJobs = array_filter($similarJobs, function($job) use ($offreemploi) {
            return $job->getId() !== $offreemploi->getId();
        });

        return $this->render('front/offreemploi/show.html.twig', [
            'offreemploi' => $offreemploi,
            'offreemplois' => $similarJobs,
        ]);
    }
}