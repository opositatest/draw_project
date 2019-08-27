<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comentario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Comentario find($id, $lockMode = null, $lockVersion = null)
 * @method null|Comentario findOneBy(array $criteria, array $orderBy = null)
 * @method Comentario[]    findAll()
 * @method Comentario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comentario::class);
    }

    public function saveComment($comentario): void
    {
        $em = $this->getEntityManager();
        $em->persist($comentario);
        $em->flush();
    }

}
