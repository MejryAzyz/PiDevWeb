<?php

namespace App\Repository;

use App\Entity\Postulation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postulation>
 */
class PostulationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postulation::class);
    }

    public function save(Postulation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Postulation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findPostulationStats(): array
    {
        return $this->createQueryBuilder('p')
            ->select('o.titre AS offer_title, COUNT(p.id_postulation) AS postulation_count')
            ->join('p.id_offre', 'o')
            ->groupBy('o.id')
            ->orderBy('postulation_count', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
