<?php

namespace App\Repository;

use App\Entity\Hebergement_photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Hebergement_photosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hebergement_photos::class);
    }

    // Add custom methods as needed
}