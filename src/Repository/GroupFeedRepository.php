<?php

namespace App\Repository;

use App\Entity\GroupFeed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupFeed|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupFeed|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupFeed[]    findAll()
 * @method GroupFeed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupFeedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupFeed::class);
    }

    // /**
    //  * @return GroupFeed[] Returns an array of GroupFeed objects
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
    public function findOneBySomeField($value): ?GroupFeed
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
