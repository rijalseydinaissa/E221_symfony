<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByDateLivraisonPrevue(\DateTimeInterface $date)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateLivraisonPrevue = :date')
            ->andWhere('c.statut = :statut')
            ->setParameter('date', $date)
            ->setParameter('statut', Commande::STATUT_EN_COURS)
            ->getQuery()
            ->getResult();
    }

    public function findCommandesNonPayees()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.statut != :statut_paye')
            ->setParameter('statut_paye', Commande::STATUT_PAYE)
            ->getQuery()
            ->getResult();
    }
}
