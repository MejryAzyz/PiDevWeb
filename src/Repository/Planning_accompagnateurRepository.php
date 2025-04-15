<?php

namespace App\Repository;

use App\Entity\Planning_accompagnateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Planning_accompagnateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning_accompagnateur::class);
    }

    // Add custom methods as needed
}