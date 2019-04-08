<?php

namespace App\Repository;

use App\Entity\JoinRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JoinRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method JoinRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method JoinRequest[]    findAll()
 * @method JoinRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoinRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JoinRequest::class);
    }

    // /**
    //  * @return JoinRequest[] Returns an array of JoinRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JoinRequest
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
