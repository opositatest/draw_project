<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Poll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Poll find($id, $lockMode = null, $lockVersion = null)
 * @method null|Poll findOneBy(array $criteria, array $orderBy = null)
 * @method Poll[]    findAll()
 * @method Poll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PollRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Poll::class);
    }

    /**
     * @param $min, $max
     * @param mixed $max
     *
     * @return Poll[]
     */
    public function findBetween($min, $max): array
    {
        $qb = $this->createQueryBuilder('enc')
            ->Where('enc.id BETWEEN :min AND :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->orderBy('enc.id', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return array
     */
    public function contarPolls(): array
    {
        $qb = $this->createQueryBuilder('num')
            ->select('DISTINCT COUNT(num.id)')
            ->from('App\Entity\Poll', 'enc')
            ->groupBy('enc.id')
            ->getQuery();

        return $qb->execute();
    }

    public function getPollById($id)
    {
        return $this->find($id);
    }

    public function getPollsOrderBy($criteria, $order, $limit, $offset): void
    {
        $em = $this->getEntityManager();
    }

}
