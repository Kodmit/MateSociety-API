<?php

namespace App\Repository;

use App\Entity\GroupFeedComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupFeedComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupFeedComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupFeedComment[]    findAll()
 * @method GroupFeedComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupFeedCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupFeedComment::class);
    }

    // /**
    //  * @return GroupFeedComment[] Returns an array of GroupFeedComment objects
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
    public function findOneBySomeField($value): ?GroupFeedComment
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
