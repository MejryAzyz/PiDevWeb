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

    public function findAllWithPhotos()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cliniquePhotos', 'p') // Charger les photos associées
            ->addSelect('p') // Inclure les données des photos
            ->getQuery()
            ->getResult();
    }

    public function findFilteredCliniques(array $filters = [], int $page = 1, int $limit = 6)
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c', 'COUNT(d.id_docteur) AS HIDDEN docteurCount')
            ->leftJoin('c.cliniquePhotos', 'photos')
            ->addSelect('photos')
            ->leftJoin('c.docteurs', 'd')
            ->leftJoin('d.specialite', 's')
            ->groupBy('c.id_clinique');

        // Appliquer les filtres
        if (!empty($filters['price_range'])) {
            list($minPrice, $maxPrice) = array_map('floatval', explode(',', $filters['price_range']));
            $queryBuilder
                ->andWhere('c.prix >= :minPrice')
                ->andWhere('c.prix <= :maxPrice')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        }

        if (!empty($filters['pays'])) {
            $queryBuilder
                ->andWhere('c.adresse LIKE :pays')
                ->setParameter('pays', '%' . $filters['pays'] . '%');
        }

        if (!empty($filters['specialty'])) {
            $queryBuilder
                ->andWhere('s.id_specialite = :specialtyId')
                ->setParameter('specialtyId', $filters['specialty']);
        }

        // Appliquer le tri
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'prix_asc':
                    $queryBuilder->orderBy('c.prix', 'ASC');
                    break;
                case 'prix_desc':
                    $queryBuilder->orderBy('c.prix', 'DESC');
                    break;
                case 'docteurs':
                    $queryBuilder->orderBy('docteurCount', 'DESC');
                    break;
                case 'recommande':
                default:
                    $queryBuilder->orderBy('docteurCount', 'DESC')
                               ->addOrderBy('c.prix', 'ASC');
                    break;
            }
        }

        // Récupérer les prix min et max
        $priceRange = $this->createQueryBuilder('c')
            ->select('MIN(c.prix) as min_price, MAX(c.prix) as max_price')
            ->getQuery()
            ->getOneOrNullResult();

        $minPrice = $priceRange['min_price'] ?? 0;
        $maxPrice = $priceRange['max_price'] ?? 1000;

        return [
            'query' => $queryBuilder->getQuery(),
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'current_min_price' => $filters['price_range'] ? explode(',', $filters['price_range'])[0] : $minPrice,
            'current_max_price' => $filters['price_range'] ? explode(',', $filters['price_range'])[1] : $maxPrice
        ];
    }

    // Add custom methods as needed
}