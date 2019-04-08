<?php

namespace App\Repository;

use App\Entity\GroupGoal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupGoal|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupGoal|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupGoal[]    findAll()
 * @method GroupGoal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupGoalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupGoal::class);
    }

    // /**
    //  * @return GroupGoal[] Returns an array of GroupGoal objects
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
    public function findOneBySomeField($value): ?GroupGoal
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
