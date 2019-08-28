<?php

declare(strict_types=1);


namespace App\Services;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use Exception;
use Psr\Log\LoggerInterface;

class SorteoService
{
    private $logger;
    private $sorteoManager;
    private $usuarioManager;

    public function __construct(LoggerInterface $logger, SorteoManager $sorteoManager, UsuarioManager $usuarioManager)
    {
        $this->logger = $logger;
        $this->sorteoManager = $sorteoManager;
        $this->usuarioManager = $usuarioManager;
    }

    /**
     * @param $userData
     * @param $date
     * @param Sorteo $sorteoActual
     *
     * @throws Exception
     *
     * @return array
     */
    public function sorteoManagerAction($userData, $date,Sorteo $sorteoActual){
        //añadir, crear o ejecutar
        $fechaSorteo = $sorteoActual->getFecha();
        if ($date < $fechaSorteo) {
            if ($sorteoActual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $respuesta = '¡Vaya! Parece que ha habido un error.';
                $titulo = 'ERROR';
                $data = [$titulo, $respuesta];

                return $data;
            }
            // SORTEO ACTIVO SIN GANADOR ---> añado usuario a sorteo

            try {
                $this->usuarioManager->addUserToSorteo($user, $sorteoActual);
                $respuesta = 'Te has inscrito al sorteo. ¡Mucha suerte!';
                $titulo = 'ENHORABUENA';
                $data = [$titulo, $respuesta];
            } catch (PasswordIncorrectException $pie) {
                $titulo = 'ERROR';
                $respuesta = $pie->getMessage();
                $data = [$titulo, $respuesta];
            } catch (AlreadySubscribedException $ase) {
                $titulo = 'ERROR';
                $respuesta = $ase->getMessage();
                $data = [$titulo, $respuesta];
            }

            return $data;
        }
        if ($sorteoActual->getGanador()) {
            // SORTEO NO ACTIVO (FECHA MENOR) Y CON GANADOR ---> creo sorteo/añado usuario

            /** @var Sorteo $newSorteo */
            $newSorteo = $this->sorteoManager->crearSorteo($fechaSorteo);

            return $this->beforeAdding($newSorteo, $user);
        }
        // SORTEO NO ACTIVO (FECHA MENOR) Y SIN GANADOR ---> ejecuto sorteo/creo sorteo/añado usuario
        $this->sorteoManager->runSorteo($sorteoActual);

        /** @var Sorteo $newSorteo */
        $newSorteo = $this->sorteoManager->crearSorteo($fechaSorteo);

        return $this->beforeAdding($newSorteo, $user);
    }

    /**
     * @param $newSorteo
     * @param $userData
     *
     * @throws Exception
     *
     * @return array
     */
    private function beforeAdding($newSorteo, $userData)
    {
        try {
            if ($newSorteo) {
                $this->usuarioManager->addUserToSorteo($userData, $newSorteo);
            }
            $respuesta = 'Atención: El sorteo anterior ha caducado. Te has inscrito a un nuevo sorteo. ¡Mucha suerte!';
            $titulo = 'ENHORABUENA';
            $data = [$titulo, $respuesta];
        } catch (PasswordIncorrectException $pie) {
            $titulo = 'ERROR';
            $respuesta = $pie->getMessage();
            $data = [$titulo, $respuesta];
        } catch (AlreadySubscribedException $ase) {
            $titulo = 'ERROR';
            $respuesta = $ase->getMessage();
            $data = [$titulo, $respuesta];
        }

        return $data;
    }
}