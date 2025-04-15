<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findByClinic(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.idClinique', 'c') // Use 'idClinique' instead of 'id_clinique'
            ->addSelect('c')
            ->andWhere('r.idClinique != 0') // Use 'idClinique' instead of 'id_clinique'
            ->getQuery()
            ->getResult();
    }

    public function findByTransport(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_transport != 0')
            ->getQuery()
            ->getResult();
    }

    public function findByHebergement(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_hebergement != 0')
            ->getQuery()
            ->getResult();
    }

    // Add custom methods as needed
}
