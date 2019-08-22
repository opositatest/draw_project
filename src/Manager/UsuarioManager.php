<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Exceptions\UserNotFoundException;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\ORMException;
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

    public function addUser($data, Sorteo $sorteoActual)
    {
        /** @var Usuario $usuario */
        $usuario = $this->getOneUsuarioBy(['email' => $data[1]]);

        if (!$usuario) {
            $pass = $data[2];
            $coded = password_hash($pass, PASSWORD_BCRYPT);
            $newUser = new Usuario();
            $newUser->setNombre($data[0]);
            $newUser->setEmail($data[1]);
            $newUser->setPassword($coded);
            $newUser->addSorteo($sorteoActual);

            try {
                $this->usuarioRepository->addUser($newUser);

                return true;
            } catch (ORMException $e) {
                $this->logger->alert($e->getMessage());
            }
        } elseif ($usuario) {
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

                if (0 === $num) {
                    // NO TIENE EL SORTEO ASOCIADO

                    $usuario->setNombre($data[0]);
                    $usuario->addSorteo($sorteoActual);

                    try {
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

    public function borrarUser($userData, Sorteo $actual, $num)
    {
        /** @var Usuario $user */
        $user = $this->getOneUsuarioBy(['email' => $userData[0]]);

        try {
            if ($user) {
                $hash = $user->getPassword();
                if (password_verify($userData[1], $hash)) {
                    $sorteos = $user->getSorteos();
                    if (!empty($sorteos)) {
                        foreach ($sorteos as $sorteo) {
                            if ($sorteo->getId() === $actual->getId()) {
                                ++$num;
                            } else {
                                $num += 0;
                            }
                        }
                    } else {
                        throw new UserNotFoundException('El usuario no está registrado en ningún sorteo');
                    }
                    if (0 === $num) {
                        $titulo = 'ERROR';
                        $respuesta = 'Este usuario no está inscrito al sorteo actual';
                        $data = [$titulo, $respuesta];
                    } elseif ($num > 0) {
                        $user->removeSorteo($actual);
                        $this->usuarioRepository->removeFromSorteo($user);
                        $titulo = '¡Operación realizada con éxito!';
                        $respuesta = 'Has sido borrado del sorteo actual';
                        $data = [$titulo, $respuesta];
                    }
                } else {
                    throw new PasswordIncorrectException('Contraseña incorrecta. Introduzca de nuevo su contraseña.');
                }
            } else {
                throw new UserNotFoundException('El usuario no está registrado en ningún sorteo');
            }
        } catch (PasswordIncorrectException $pie) {
            $titulo = 'ERROR';
            $respuesta = $pie->getMessage();
            $data = [$titulo, $respuesta];
        } catch (UserNotFoundException $unfe) {
            $titulo = 'ERROR';
            $respuesta = $unfe->getMessage();
            $data = [$titulo, $respuesta];
        }

        return $data;
    }
}
