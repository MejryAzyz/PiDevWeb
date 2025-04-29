<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPendingPostulations(int $limit = 10): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT p.id_postulation, a.username, o.titre AS offre_titre, p.date_postulation
             FROM App\Entity\Postulation p
             LEFT JOIN p.id_accompagnateur a
             LEFT JOIN p.id_offre o
             WHERE p.statut = :statut
             ORDER BY p.date_postulation DESC'
        )
            ->setParameter('statut', 'Pending')
            ->setMaxResults($limit);

        $postulations = $query->getResult();

        return array_map(function ($postulation) {
            return [
                'id_postulation' => $postulation['id_postulation'],
                'username' => $postulation['username'] ?? 'Unknown',
                'offre_titre' => $postulation['offre_titre'] ?? 'Unknown Offer',
                'time_ago' => $this->getTimeAgo($postulation['date_postulation']),
            ];
        }, $postulations);
    }

    private function getTimeAgo(\DateTimeInterface $date): string
    {
        $now = new \DateTime();
        $interval = $now->diff($date);
        if ($interval->y > 0) return $interval->y . 'y ago';
        if ($interval->m > 0) return $interval->m . 'm ago';
        if ($interval->d > 0) return $interval->d . 'd ago';
        if ($interval->h > 0) return $interval->h . 'h ago';
        if ($interval->i > 0) return $interval->i . 'min ago';
        return 'just now';
    }
}
