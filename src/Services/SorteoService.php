<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 22/05/18
 * Time: 10:47
 */

namespace App\Services;


use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\GanadorNotSettedException;
use App\Exceptions\PasswordIncorrectException;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use DateInterval;
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
     * @param $fecha_sorteo
     * @param Sorteo $sorteo_actual
     * @return array
     * @throws Exception
     */
    public function sorteoManagerAction($userData, $date, $fecha_sorteo,Sorteo $sorteo_actual){
        //añadir, crear o ejecutar
        if ($date < $fecha_sorteo) {
            if ($sorteo_actual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $respuesta = "¡Vaya! Parece que ha habido un error.";
                $titulo = "ERROR";
                $data = [$titulo, $respuesta];
                return $data;
            } else {
                // SORTEO ACTIVO SIN GANADOR ---> añado usuario a sorteo

                try {
                    $this->usuarioManager->addUser($userData, $sorteo_actual);
                    $respuesta = "Te has inscrito al sorteo. ¡Mucha suerte!";
                    $titulo ="ENHORABUENA";
                    $data = [$titulo, $respuesta];
                }catch (PasswordIncorrectException $pie ) {
                    $titulo ="ERROR";
                    $respuesta = $pie->getMessage();
                    $data = [$titulo, $respuesta];
                }catch (AlreadySubscribedException $ase) {
                    $titulo ="ERROR";
                    $respuesta = $ase->getMessage();
                    $data = [$titulo, $respuesta];
                }
                return $data;
            }
        } else{
            if ($sorteo_actual->getGanador()) {
                // SORTEO NO ACTIVO (FECHA MENOR) Y CON GANADOR ---> creo sorteo/añado usuario

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->sorteoManager->crearSorteo($fecha_sorteo);

                return $this->beforeAdding($newSorteo, $userData);
            } else {
                // SORTEO NO ACTIVO (FECHA MENOR) Y SIN GANADOR ---> ejecuto sorteo/creo sorteo/añado usuario
                $this->sorteoManager->runSorteo($sorteo_actual);

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->sorteoManager->crearSorteo($fecha_sorteo);

                return $this->beforeAdding($newSorteo, $userData);
            }
        }
    }


    /**
     * @param $newSorteo
     * @param $userData
     * @return array
     * @throws Exception
     */
    private function beforeAdding($newSorteo, $userData)
    {
        try {
            if ($newSorteo) {
                $this->usuarioManager->addUser($userData, $newSorteo);
            }
            $respuesta = "Atención: El sorteo anterior ha caducado. Te has inscrito a un nuevo sorteo. ¡Mucha suerte!";
            $titulo ="ENHORABUENA";
            $data = [$titulo, $respuesta];
        }catch (PasswordIncorrectException $pie ) {
            $titulo ="ERROR";
            $respuesta = $pie->getMessage();
            $data = [$titulo, $respuesta];
        }catch (AlreadySubscribedException $ase) {
            $titulo ="ERROR";
            $respuesta = $ase->getMessage();
            $data = [$titulo, $respuesta];
        }

        return $data;
    }

}