<?php

namespace App\Repository;

use App\Entity\Clinique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CliniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clinique::class);
    }

<<<<<<< HEAD
=======
    public function findAllWithPhotos()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cliniquePhotos', 'p') // Charger les photos associées
            ->addSelect('p') // Inclure les données des photos
            ->getQuery()
            ->getResult();
    }

>>>>>>> c4098f6 (bundle)
    // Add custom methods as needed
}