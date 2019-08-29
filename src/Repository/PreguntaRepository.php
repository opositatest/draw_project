<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Pregunta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Pregunta find($id, $lockMode = null, $lockVersion = null)
 * @method null|Pregunta findOneBy(array $criteria, array $orderBy = null)
 * @method Pregunta[]    findAll()
 * @method Pregunta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pregunta::class);
    }

    public function addQuestion($pregunta)
    {
        $em = $this->getEntityManager();
        $em->persist($pregunta);
        $em->flush();
    }

}
