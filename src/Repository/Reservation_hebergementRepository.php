<?php

namespace App\Repository;

use App\Entity\Reservation_Hebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Reservation_hebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation_hebergement::class);
    }

    // Add custom methods as needed
}