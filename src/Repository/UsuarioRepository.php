<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
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
        $this->findOneBy($criteria);
    }
    public function addUser($user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
    public function removeFromSorteo($user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
