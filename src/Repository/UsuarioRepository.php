<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Usuario find($id, $lockMode = null, $lockVersion = null)
 * @method null|Usuario findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function getOneUsuarioBy($criteria)
    {
        return $this->findOneBy($criteria);
    }

    public function addUser($user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function removeFromSorteo($user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
