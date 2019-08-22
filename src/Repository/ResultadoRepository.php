<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Resultado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Resultado find($id, $lockMode = null, $lockVersion = null)
 * @method null|Resultado findOneBy(array $criteria, array $orderBy = null)
 * @method Resultado[]    findAll()
 * @method Resultado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resultado::class);
    }

//    /**
//     * @return Resultado[] Returns an array of Resultado objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Resultado
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
