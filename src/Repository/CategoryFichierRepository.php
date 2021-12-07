<?php

namespace App\Repository;

use App\Entity\CategoryFichier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryFichier|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryFichier|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryFichier[]    findAll()
 * @method CategoryFichier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryFichierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryFichier::class);
    }

    // /**
    //  * @return CategoryFichier[] Returns an array of CategoryFichier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryFichier
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
