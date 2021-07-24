<?php

namespace App\Repository;

use App\Entity\Eclat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Eclat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eclat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eclat[]    findAll()
 * @method Eclat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EclatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eclat::class);
    }

    // /**
    //  * @return Eclat[] Returns an array of Eclat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Eclat
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
