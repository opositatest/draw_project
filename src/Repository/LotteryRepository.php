<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Lottery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Lottery find($id, $lockMode = null, $lockVersion = null)
 * @method null|Lottery findOneBy(array $criteria, array $orderBy = null)
 * @method Lottery[]    findAll()
 * @method Lottery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotteryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lottery::class);
    }

    /**
     * @param $min, $max
     * @param mixed $max
     *
     * @return Lottery[]
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
    public function contarLotteries(): array
    {
        $qb = $this->createQueryBuilder('num')
            ->select('DISTINCT COUNT(num.id)')
            ->from('App\Entity\Lottery', 'sor')
            ->groupBy('sor.id')
            ->getQuery();

        return $qb->execute();
    }

    public function findLotteryOrderBy($criteria, $order, $limit, $offset)
    {
        return $this->findBy($criteria, $order, $limit, $offset);
    }

    public function addLottery($lottery): void
    {
        $em = $this->getEntityManager();

        $em->persist($lottery);
        $em->flush();
    }

    public function saveLottery($lottery): void
    {
        $em = $this->getEntityManager();
        $em->persist($lottery);
        $em->flush();
    }
}
