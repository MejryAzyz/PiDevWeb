<?php

namespace App\Repository;

use App\Entity\Reservation_transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Reservation_transportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation_transport::class);
    }

    // Add custom methods as needed
}