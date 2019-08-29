<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Encuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Encuesta find($id, $lockMode = null, $lockVersion = null)
 * @method null|Encuesta findOneBy(array $criteria, array $orderBy = null)
 * @method Encuesta[]    findAll()
 * @method Encuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuestaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Encuesta::class);
    }

    /**
     * @param $min, $max
     * @param mixed $max
     *
     * @return Encuesta[]
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
    public function contarEncuestas(): array
    {
        $qb = $this->createQueryBuilder('num')
            ->select('DISTINCT COUNT(num.id)')
            ->from('App\Entity\Encuesta', 'enc')
            ->groupBy('enc.id')
            ->getQuery();

        return $qb->execute();
    }

    public function getEncuestaById($id)
    {
        return $this->find($id);
    }

    public function getEncuestasOrderBy($criteria, $order, $limit, $offset): void
    {
        $em = $this->getEntityManager();
    }

}
