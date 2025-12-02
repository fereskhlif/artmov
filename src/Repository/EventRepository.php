<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Find all events (returns Query for pagination)
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->getQuery();
    }

    /**
     * Search events by title or location
     */
    public function searchEvents(string $searchTerm): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.titre LIKE :searchTerm OR e.lieu LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find events by price range
     */
    public function findByPriceRange(int $minPrice, int $maxPrice): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.prix BETWEEN :minPrice AND :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('e.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find events sorted by price (low to high)
     */
    public function findAllSortedByPriceAsc(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find events sorted by price (high to low)
     */
    public function findAllSortedByPriceDesc(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find events by multiple criteria (returns Query for pagination)
     */
    public function findEventsByCriteriaQuery(array $criteria): Query
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC');

        if (isset($criteria['search']) && $criteria['search']) {
            $qb->andWhere('e.titre LIKE :search OR e.lieu LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        if (isset($criteria['min_price']) && $criteria['min_price']) {
            $qb->andWhere('e.prix >= :minPrice')
               ->setParameter('minPrice', $criteria['min_price']);
        }

        if (isset($criteria['max_price']) && $criteria['max_price']) {
            $qb->andWhere('e.prix <= :maxPrice')
               ->setParameter('maxPrice', $criteria['max_price']);
        }

        if (isset($criteria['sort_by'])) {
            switch ($criteria['sort_by']) {
                case 'price_asc':
                    $qb->orderBy('e.prix', 'ASC');
                    break;
                case 'price_desc':
                    $qb->orderBy('e.prix', 'DESC');
                    break;
                case 'name_asc':
                    $qb->orderBy('e.titre', 'ASC');
                    break;
                case 'name_desc':
                    $qb->orderBy('e.titre', 'DESC');
                    break;
                default:
                    $qb->orderBy('e.id', 'DESC');
            }
        }

        return $qb->getQuery();
    }



    public function findPopularEventsQuery(): Query
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.tickets', 't')
            ->addSelect('e, COUNT(t.id) as ticketCount')
            ->groupBy('e.id')
            ->orderBy('ticketCount', 'DESC')
            ->addOrderBy('e.id', 'DESC')
            ->getQuery();
    }

    public function findPopularEventsSimpleQuery(): Query
    {
        return $this->createQueryBuilder('e')
            ->select('e as event, COUNT(t.id) as ticketCount')
            ->leftJoin('e.tickets', 't')
            ->groupBy('e.id')
            ->orderBy('ticketCount', 'DESC')
            ->addOrderBy('e.id', 'DESC')
            ->getQuery();
    }

    /**
     * Find events with revenue calculation
     */
    public function findEventsWithRevenueQuery(): Query
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.tickets', 't')
            ->addSelect('e, SUM(t.prixtot) as totalRevenue, COUNT(t.id) as totalTickets')
            ->groupBy('e.id')
            ->orderBy('totalRevenue', 'DESC')
            ->getQuery();
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
