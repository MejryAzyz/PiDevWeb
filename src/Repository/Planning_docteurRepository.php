<?php

namespace App\Repository;

use App\Entity\Planning_docteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Planning_docteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning_docteur::class);
    }

    // Add custom methods as needed
}