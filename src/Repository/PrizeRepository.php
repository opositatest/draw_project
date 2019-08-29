<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Prize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Prize find($id, $lockMode = null, $lockVersion = null)
 * @method null|Prize findOneBy(array $criteria, array $orderBy = null)
 * @method Prize[]    findAll()
 * @method Prize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrizeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prize::class);
    }

    public function getAllPrizes()
    {
        return $this->findAll();
    }

//    /**
//     * @return Prize[] Returns an array of Prize objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prize
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
