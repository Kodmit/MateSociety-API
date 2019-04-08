<?php

namespace App\Repository;

use App\Entity\Gate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gate[]    findAll()
 * @method Gate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gate::class);
    }

    // /**
    //  * @return Gate[] Returns an array of Gate objects
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
    public function findOneBySomeField($value): ?Gate
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
