<?php
/**
 * Created by PhpStorm.
 * User: pablogarcia
 * Date: 22/05/18
 * Time: 10:47
 */

namespace App\Services;


use App\Entity\Sorteo;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use Exception;

class SorteoService
{
    private $sorteoManager;
    private $usuarioManager;

    public function __construct(SorteoManager $sorteoManager, UsuarioManager $usuarioManager)
    {
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
    public function sorteoManagerAction($userData, $date,Sorteo $sorteoActual){
        //añadir, crear o ejecutar
        $fechaSorteo = $sorteoActual->getFecha();
        if ($date < $fechaSorteo) {
            if ($sorteoActual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $respuesta = "¡Vaya! Parece que ha habido un error.";
                $titulo = "ERROR";
                $data = [$titulo, $respuesta];
                return $data;
            } else {
                // SORTEO ACTIVO SIN GANADOR ---> añado usuario a sorteo

                try {
                    $this->usuarioManager->addUser($userData, $sorteoActual);
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
            if ($sorteoActual->getGanador()) {
                // SORTEO NO ACTIVO (FECHA MENOR) Y CON GANADOR ---> creo sorteo/añado usuario

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->sorteoManager->crearSorteo($fechaSorteo);

                return $this->beforeAdding($newSorteo, $userData);
            } else {
                // SORTEO NO ACTIVO (FECHA MENOR) Y SIN GANADOR ---> ejecuto sorteo/creo sorteo/añado usuario
                $this->sorteoManager->runSorteo($sorteoActual);

                /** @var Sorteo $newSorteo */
                $newSorteo = $this->sorteoManager->crearSorteo($fechaSorteo);

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