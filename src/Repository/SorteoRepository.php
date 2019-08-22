<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sorteo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Sorteo find($id, $lockMode = null, $lockVersion = null)
 * @method null|Sorteo findOneBy(array $criteria, array $orderBy = null)
 * @method Sorteo[]    findAll()
 * @method Sorteo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SorteoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sorteo::class);
    }

    /**
     * @param $min, $max
     * @param mixed $max
     *
     * @return Sorteo[]
     */
    public function findBetween($max, $min): array
    {
        $qb = $this->createQueryBuilder('sor')
            ->Where('sor.id BETWEEN :min AND :max')
            ->setParameter('min', $max)
            ->setParameter('max', $min)
            ->orderBy('sor.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return array
     */
    public function contarSorteos(): array
    {
        $qb = $this->createQueryBuilder('num')
            ->select('DISTINCT COUNT(num.id)')
            ->from('App\Entity\Sorteo', 'sor')
            ->groupBy('sor.id')
            ->getQuery();

        return $qb->execute();
    }

    public function findSorteoOrderBy($criteria, $order, $limit, $offset)
    {
        return $this->findBy($criteria, $order, $limit, $offset);
    }

    public function addSorteo($sorteo): void
    {
        $em = $this->getEntityManager();

        $em->persist($sorteo);
        $em->flush();
    }

    public function finishSorteo($sorteo): void
    {
        $em = $this->getEntityManager();
        $em->persist($sorteo);
        $em->flush();
    }
}
