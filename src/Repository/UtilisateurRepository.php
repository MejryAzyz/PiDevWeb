<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function findByFilters(?string $search, ?string $nationalite, ?string $status): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($search) {
            $qb->andWhere('u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($nationalite) {
            $qb->andWhere('u.nationalite = :nationalite')
               ->setParameter('nationalite', $nationalite);
        }

        if ($status !== '') {
            $qb->andWhere('u.status = :status')
               ->setParameter('status', (int)$status);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByFiltersQuery(?string $search, ?string $nationalite, ?string $status, string $sortDirection = 'asc'): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        if ($search) {
            $qb->andWhere('u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($nationalite) {
            $qb->andWhere('u.nationalite = :nationalite')
               ->setParameter('nationalite', $nationalite);
        }

        if ($status !== '') {
            $qb->andWhere('u.status = :status')
               ->setParameter('status', (int)$status);
        }

        // Add sorting by ID
        $qb->orderBy('u.id_utilisateur', $sortDirection);

        return $qb;
    }

    // Add custom methods as needed
}