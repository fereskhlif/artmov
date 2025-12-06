<?php

namespace App\Repository;

use App\Entity\Trajet;
use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transport>
 */
class TrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajet::class);
    }
    public function showAlltrajetByVehicule(int $id)

    {
        $qb=$this->createQueryBuilder('b')
            ->join('b.vehicule', 'v')
            ->where('v.id=:x')
            ->setParameter('x', $id);
        return $qb->getQuery()->getResult();
    }
    public function showAlltrajetOrderByVilleDep()
    {
        $qb=$this->createQueryBuilder('b')
            ->orderBy('b.ville_dep','ASC');
        return $qb->getQuery()->getResult();

    }
    public function searchVehiculeByDateDep(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('t')
            ->where('DATE(t.date_dep) = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Transport[] Returns an array of Transport objects
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

    //    public function findOneBySomeField($value): ?Transport
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
