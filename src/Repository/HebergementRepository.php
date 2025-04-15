<?php

namespace App\Repository;

use App\Entity\Hebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class HebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hebergement::class);
    }

    /**
     * Récupère les hébergements filtrés et paginés
     */
    public function findFilteredHebergements(
        string $search = '',
        float $minPrice = 0,
        float $maxPrice = 1000,
        int $capacityMin = 0,
        int $capacityMax = 1000,
        string $nation = '',
        string $sortBy = 'recommended',
        string $sortOrder = 'asc',
        int $page = 1,
        int $limit = 5
    ): array {
        $qb = $this->createFilteredQueryBuilder($search, $minPrice, $maxPrice, $capacityMin, $capacityMax, $nation);
        
        // Appliquer le tri
        switch ($sortBy) {
            case 'price':
                $qb->orderBy('h.tarif_nuit', $sortOrder);
                break;
            case 'capacity':
                $qb->orderBy('h.capacite', $sortOrder);
                break;
            case 'name':
                $qb->orderBy('h.nom', $sortOrder);
                break;
            case 'address':
                $qb->orderBy('h.adresse', $sortOrder);
                break;
            case 'recommended':
            default:
                // Par défaut, trier par ID (les plus récents d'abord)
                $qb->orderBy('h.id_hebergement', 'DESC');
                break;
        }
        
        // Appliquer la pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Compte le nombre total d'hébergements filtrés
     */
    public function countFilteredHebergements(
        string $search = '',
        float $minPrice = 0,
        float $maxPrice = 1000,
        int $capacityMin = 0,
        int $capacityMax = 1000,
        string $nation = ''
    ): int {
        $qb = $this->createFilteredQueryBuilder($search, $minPrice, $maxPrice, $capacityMin, $capacityMax, $nation);
        $qb->select('COUNT(h.id_hebergement)');
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * Récupère les capacités uniques pour le filtre
     */
    public function findUniqueCapacities(): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select('DISTINCT h.capacite')
            ->orderBy('h.capacite', 'ASC');
        
        $result = $qb->getQuery()->getResult();
        
        // Extraire les valeurs de capacité
        $capacities = [];
        foreach ($result as $row) {
            $capacities[] = $row['capacite'];
        }
        
        return $capacities;
    }
    
    /**
     * Récupère la plage de prix min et max
     */
    public function findPriceRange(): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select('MIN(h.tarif_nuit) as minPrice, MAX(h.tarif_nuit) as maxPrice');
        
        $result = $qb->getQuery()->getOneOrNullResult();
        
        // S'assurer que les valeurs sont des nombres et non null
        $minPrice = $result['minPrice'] !== null ? (float)$result['minPrice'] : 0;
        $maxPrice = $result['maxPrice'] !== null ? (float)$result['maxPrice'] : 1000;
        
        return [
            'min' => $minPrice,
            'max' => $maxPrice
        ];
    }
    
    /**
     * Récupère les nations uniques pour le filtre
     */
    public function findUniqueNations(): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select('DISTINCT h.adresse')
            ->orderBy('h.adresse', 'ASC');
        
        $result = $qb->getQuery()->getResult();
        
        // Extraire les nations des adresses
        $nations = [];
        foreach ($result as $row) {
            $address = $row['adresse'];
            // Extraire la nation de l'adresse (dernier mot après la dernière virgule)
            $parts = explode(',', $address);
            if (count($parts) > 0) {
                $nation = trim(end($parts));
                if (!in_array($nation, $nations)) {
                    $nations[] = $nation;
                }
            }
        }
        
        sort($nations);
        return $nations;
    }
    
    /**
     * Crée un QueryBuilder avec les filtres appliqués
     */
    private function createFilteredQueryBuilder(
        string $search = '',
        float $minPrice = 0,
        float $maxPrice = 1000,
        int $capacityMin = 0,
        int $capacityMax = 1000,
        string $nation = ''
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('h');
        
        // Filtre par recherche
        if (!empty($search)) {
            $qb->andWhere('h.nom LIKE :search OR h.adresse LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        // Filtre par prix
        $qb->andWhere('h.tarif_nuit >= :minPrice')
           ->andWhere('h.tarif_nuit <= :maxPrice')
           ->setParameter('minPrice', $minPrice)
           ->setParameter('maxPrice', $maxPrice);
        
        // Filtre par capacité
        $qb->andWhere('h.capacite >= :capacityMin')
           ->andWhere('h.capacite <= :capacityMax')
           ->setParameter('capacityMin', $capacityMin)
           ->setParameter('capacityMax', $capacityMax);
        
        // Filtre par nation
        if (!empty($nation)) {
            $qb->andWhere('h.adresse LIKE :nation')
               ->setParameter('nation', '%, ' . $nation);
        }
        
        return $qb;
    }

    // Add custom methods as needed
}