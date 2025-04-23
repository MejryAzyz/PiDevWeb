<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }
    // Add the custom method to find payments by id_reservation
    public function findAllByIdreservation(int $id_reservation)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id_reservation = :id_reservation')
            ->setParameter('id_reservation', $id_reservation)
            ->getQuery()
            ->getResult();
    }
    // Add custom methods as needed
}
