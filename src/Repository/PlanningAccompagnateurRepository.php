<?php

namespace App\Repository;

use App\Entity\PlanningAccompagnateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanningAccompagnateur>
 */
class PlanningAccompagnateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningAccompagnateur::class);
    }

    //    /**
    //     * @return PlanningAccompagnateur[] Returns an array of PlanningAccompagnateur objects
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

    //    public function findOneBySomeField($value): ?PlanningAccompagnateur
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
            ->leftJoin('p.accompagnateur', 'a')
            ->leftJoin('p.dossierMedical', 'dm');

        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('a.username', ':query'),
                $qb->expr()->like('p.date_jour', ':query'),
                $qb->expr()->like('p.heure_debut', ':query'),
                $qb->expr()->like('p.heure_fin', ':query'),
                $qb->expr()->like('dm.nomPatient', ':query')
            )
        )
        ->setParameter('query', '%' . $query . '%')
        ->orderBy('p.date_jour', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getSearchSuggestions(string $query): array
    {
        $suggestions = [];

        try {
            // Get accompagnateur suggestions
            $qb = $this->createQueryBuilder('p')
                ->select('DISTINCT a.username as accompagnateur')
                ->leftJoin('p.accompagnateur', 'a')
                ->where('a.username LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5);

            $accompagnateurs = $qb->getQuery()->getResult();
            foreach ($accompagnateurs as $accompagnateur) {
                if (!empty($accompagnateur['accompagnateur'])) {
                    $suggestions[] = $accompagnateur['accompagnateur'];
                }
            }

            // Get date suggestions
            $qb = $this->createQueryBuilder('p')
                ->select('DISTINCT p.date_jour as date')
                ->where('p.date_jour LIKE :query')
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
                ->select('DISTINCT p.heure_debut as heure')
                ->where('p.heure_debut LIKE :query')
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
