<?php

namespace App\Repository;

use App\Entity\Offre_emploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Offre_emploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre_emploi::class);
    }

    // Add custom methods as needed
}