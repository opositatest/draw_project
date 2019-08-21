<?php

namespace App\Manager;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class UsuarioManager{
    private $usuarioRepository;
    private $logger;

    public function __construct(UsuarioRepository $usuarioRepository, LoggerInterface $logger)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->logger = $logger;
    }

    public function getOneUsuarioBy($criteria){
        return $this->usuarioRepository->getOneUsuarioBy($criteria);
    }

    public function addUser($data, Sorteo $sorteoActual){
        /** @var Usuario $usuario */
        $usuario = $this->getOneUsuarioBy(array('email' => $data[1]));

        if (!$usuario){
            $pass = $data[2];
            $coded = password_hash($pass, PASSWORD_BCRYPT);
            $newUser = new Usuario();
            $newUser->setNombre($data[0]);
            $newUser->setEmail($data[1]);
            $newUser->setPassword($coded);
            $newUser->addSorteo($sorteoActual);

            try{
                $this->usuarioRepository->addUser($newUser);
                return true;
            }catch (ORMException $e) {
                $this->logger->alert($e->getMessage());
            }
        } else if ($usuario) {
            // EXISTE USUARIO EN BD ---> compruebo contraseña correcta

            $hash = $usuario->getPassword();

            if (password_verify($data[2], $hash)) {
                // CONTRASEÑA CORRECTA ---> compruebo que no este en el sorteo actual
                $num = 0;
                $sorteos = $usuario->getSorteos()->getValues();
                /** @var Sorteo $sorteo */
                foreach ($sorteos as $sorteo) {
                    if ($sorteo->getId() === $sorteoActual->getId()) {
                        $num = $num + 1;
                    } else {
                        $num = $num + 0;
                    }
                }

                if ($num === 0) {
                    // NO TIENE EL SORTEO ASOCIADO

                    $usuario->setNombre($data[0]);
                    $usuario->addSorteo($sorteoActual);

                    try{
                        $this->usuarioRepository->addUser($usuario);
                        return true;
                    } catch (ORMException $e) {
                        $this->logger->alert($e->getMessage());
                    }

                } else {
                    // YA ESTA INSCRITO EN EL SORTEO
                    throw new AlreadySubscribedException('¡Ya estás inscrito en el sorteo!');
                }
            } else {
                // CONTRASEÑA INCORRECTA
                throw new PasswordIncorrectException('Ha habido un error al añadirte al sorteo, comprueba tu email y contraseña');
            }
        }
    }
}