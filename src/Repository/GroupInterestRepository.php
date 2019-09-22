<?php

namespace App\Repository;

use App\Entity\GroupInterest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupInterest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupInterest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupInterest[]    findAll()
 * @method GroupInterest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupInterestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupInterest::class);
    }

    // /**
    //  * @return GroupInterest[] Returns an array of GroupInterest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupInterest
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
