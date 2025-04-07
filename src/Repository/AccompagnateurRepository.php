<?php

namespace App\Repository;

use App\Entity\Accompagnateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccompagnateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accompagnateur::class);
    }

    // Add custom methods as needed
}