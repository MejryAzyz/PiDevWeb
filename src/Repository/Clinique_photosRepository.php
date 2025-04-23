<?php

namespace App\Repository;

use App\Entity\Clinique_photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Clinique_photosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clinique_photos::class);
    }

    // Add custom methods as needed
}