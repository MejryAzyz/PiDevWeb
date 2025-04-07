<?php

namespace App\Repository;

use App\Entity\Affectation_accompagnateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Affectation_accompagnateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation_accompagnateur::class);
    }

    // Add custom methods as needed
}