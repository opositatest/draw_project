<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;
use App\Repository\UsuarioRepository;
use Psr\Log\LoggerInterface;

class UsuarioManager
{
    private $usuarioRepository;
    private $logger;

    public function __construct(UsuarioRepository $usuarioRepository, LoggerInterface $logger)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->logger = $logger;
    }

    public function getOneUsuarioBy($criteria)
    {
        return $this->usuarioRepository->getOneUsuarioBy($criteria);
    }

    public function newUser(Usuario $user)
    {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
        $this->usuarioRepository->addUser($user);
    }

    public function addUserToSorteo(Usuario $user, Sorteo $sorteo){
        $user->addSorteo($sorteo);
        $this->usuarioRepository->addUser($user);
    }

    public function borrarUserFromSorteo($user, Sorteo $actual, $num)
    {
        $this->usuarioRepository->removeFromSorteo($user, $actual);
    }
}
