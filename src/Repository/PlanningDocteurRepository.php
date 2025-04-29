<?php

namespace App\Repository;

use App\Entity\PlanningDocteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<PlanningDocteur>
 */
class PlanningDocteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningDocteur::class);
    }

    //    /**
    //     * @return PlanningDocteur[] Returns an array of PlanningDocteur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PlanningDocteur
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function search(string $query): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.docteur', 'd')
            ->leftJoin('p.dossierMedical', 'dm');

        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('d.nom', ':query'),
                $qb->expr()->like('p.dateJour', ':query'),
                $qb->expr()->like('p.heureDebut', ':query'),
                $qb->expr()->like('p.heureFin', ':query'),
                $qb->expr()->like('dm.nomPatient', ':query')
            )
        )
        ->setParameter('query', '%' . $query . '%')
        ->orderBy('p.dateJour', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getSearchSuggestions(string $query): array
    {
        $suggestions = [];

        try {
            // Get doctor suggestions
            $qb = $this->createQueryBuilder('p')
                ->select('DISTINCT d.nom as docteur')
                ->leftJoin('p.docteur', 'd')
                ->where('d.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5);

            $docteurs = $qb->getQuery()->getResult();
            foreach ($docteurs as $docteur) {
                if (!empty($docteur['docteur'])) {
                    $suggestions[] = $docteur['docteur'];
                }
            }

            // Get date suggestions
            $qb = $this->createQueryBuilder('p')
                ->select('DISTINCT p.dateJour as date')
                ->where('p.dateJour LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5);

            $dates = $qb->getQuery()->getResult();
            foreach ($dates as $date) {
                if ($date['date'] instanceof \DateTimeInterface) {
                    $suggestions[] = $date['date']->format('Y-m-d');
                }
            }

            // Get time suggestions
            $qb = $this->createQueryBuilder('p')
                ->select('DISTINCT p.heureDebut as heure')
                ->where('p.heureDebut LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5);

            $heures = $qb->getQuery()->getResult();
            foreach ($heures as $heure) {
                if (!empty($heure['heure'])) {
                    $suggestions[] = $heure['heure'];
                }
            }

            // Remove duplicates and sort
            $suggestions = array_unique($suggestions);
            sort($suggestions);

            return $suggestions;
        } catch (\Exception $e) {
            // Log the error
            error_log('Error in getSearchSuggestions: ' . $e->getMessage());
            return [];
        }
    }
}
