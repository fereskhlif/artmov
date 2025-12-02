<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Find all tickets (returns Query for pagination)
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.event', 'e')
            ->addSelect('e')
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }

    /**
     * Find tickets by event (returns Query for pagination)
     */
    public function findByEventQuery(int $eventId): Query
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.event', 'e')
            ->addSelect('e')
            ->andWhere('e.id = :eventId')
            ->setParameter('eventId', $eventId)
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }

    /**
     * Find tickets with total revenue
     */
    public function getTotalRevenue(): float
    {
        $result = $this->createQueryBuilder('t')
            ->select('SUM(t.prixtot) as total')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : 0;
    }

    /**
     * Get ticket statistics by event
     */
    public function getTicketStatistics(): array
    {
        return $this->createQueryBuilder('t')
            ->select('e.titre as event_title, COUNT(t.id) as ticket_count, SUM(t.quantity) as total_quantity, SUM(t.prixtot) as total_revenue')
            ->leftJoin('t.event', 'e')
            ->groupBy('e.id')
            ->orderBy('total_revenue', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Ticket[] Returns an array of Ticket objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ticket
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
