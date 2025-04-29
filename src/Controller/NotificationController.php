<?php

namespace App\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notifications')]
class NotificationController extends AbstractController
{
    #[Route('', name: 'app_notifications', methods: ['GET'])]
    public function index(NotificationService $notificationService): JsonResponse
    {
        $postulations = $notificationService->getPendingPostulations(10);
        return $this->json(['postulations' => $postulations]);
    }

    #[Route('/all', name: 'app_notifications_all', methods: ['GET'])]
    public function all(NotificationService $notificationService): JsonResponse
    {
        $postulations = $notificationService->getPendingPostulations();
        return $this->json(['postulations' => $postulations]);
    }
}
