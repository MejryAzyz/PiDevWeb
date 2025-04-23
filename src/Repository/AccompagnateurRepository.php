<?php

namespace App\Repository;

use App\Entity\Accompagnateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accompagnateur>
 *
 * @method Accompagnateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accompagnateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accompagnateur[]    findAll()
 * @method Accompagnateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccompagnateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accompagnateur::class);
    }

    public function save(Accompagnateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Accompagnateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
} 