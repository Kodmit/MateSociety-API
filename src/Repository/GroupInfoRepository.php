<?php

namespace App\Repository;

use App\Entity\GroupInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupInfo[]    findAll()
 * @method GroupInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupInfo::class);
    }

    // /**
    //  * @return GroupInfo[] Returns an array of GroupInfo objects
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
    public function findOneBySomeField($value): ?GroupInfo
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
