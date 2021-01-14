<?php

namespace App\Repository;

use App\Entity\LegalMention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LegalMention|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalMention|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalMention[]    findAll()
 * @method LegalMention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalMentionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalMention::class);
    }

    // /**
    //  * @return LegalMention[] Returns an array of LegalMention objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LegalMention
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
