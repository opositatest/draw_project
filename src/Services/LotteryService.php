<?php

declare(strict_types=1);


namespace App\Services;

use App\Entity\Lottery;
use App\Entity\User;
use App\Exceptions\AlreadySubscribedException;
use App\Exceptions\PasswordIncorrectException;
use App\Manager\LotteryManager;
use App\Manager\UserManager;
use Exception;
use Psr\Log\LoggerInterface;

class LotteryService
{
    private $logger;
    private $lotteryManager;
    private $userManager;

    public function __construct(LoggerInterface $logger, LotteryManager $lotteryManager, UserManager $userManager)
    {
        $this->logger = $logger;
        $this->lotteryManager = $lotteryManager;
        $this->userManager = $userManager;
    }

    /**
     * @param $userData
     * @param $date
     * @param $fecha_lottery
     * @param Lottery $lottery_actual
     *
     * @throws Exception
     *
     * @return array
     */
    public function lotteryManagerAction($user, $date, $fecha_lottery, Lottery $lottery_actual)
    {
        //añadir, crear o ejecutar
        if ($date < $fecha_lottery) {
            if ($lottery_actual->getGanador()) {
                // SORTEO ACTIVO CON GANADOR ---> error, no debería de pasar nunca
                $answer = '¡Vaya! Parece que ha habido un error.';
                $titulo = 'ERROR';
                $data = [$titulo, $answer];

                return $data;
            }
            // SORTEO ACTIVO SIN GANADOR ---> añado user a lottery

            try {
                $this->userManager->addUserToLottery($user, $lottery_actual);
                $answer = 'Te has inscrito al lottery. ¡Mucha suerte!';
                $titulo = 'ENHORABUENA';
                $data = [$titulo, $answer];
            } catch (PasswordIncorrectException $pie) {
                $titulo = 'ERROR';
                $answer = $pie->getMessage();
                $data = [$titulo, $answer];
            } catch (AlreadySubscribedException $ase) {
                $titulo = 'ERROR';
                $answer = $ase->getMessage();
                $data = [$titulo, $answer];
            }

            return $data;
        }
        if ($lottery_actual->getGanador()) {
            // SORTEO NO ACTIVO (FECHA MENOR) Y CON GANADOR ---> creo lottery/añado user

            /** @var Lottery $newLottery */
            $newLottery = $this->lotteryManager->crearLottery($fecha_lottery);

            return $this->beforeAdding($newLottery, $user);
        }
        // SORTEO NO ACTIVO (FECHA MENOR) Y SIN GANADOR ---> ejecuto lottery/creo lottery/añado user
        $this->lotteryManager->runLottery($lottery_actual);

        /** @var Lottery $newLottery */
        $newLottery = $this->lotteryManager->crearLottery($fecha_lottery);

        return $this->beforeAdding($newLottery, $user);
    }

    /**
     * @param $newLottery
     * @param $userData
     *
     * @throws Exception
     *
     * @return array
     */
    private function beforeAdding($newLottery, $userData)
    {
        try {
            if ($newLottery) {
                $this->userManager->addUserToLottery($userData, $newLottery);
            }
            $answer = 'Atención: El lottery anterior ha caducado. Te has inscrito a un nuevo lottery. ¡Mucha suerte!';
            $titulo = 'ENHORABUENA';
            $data = [$titulo, $answer];
        } catch (PasswordIncorrectException $pie) {
            $titulo = 'ERROR';
            $answer = $pie->getMessage();
            $data = [$titulo, $answer];
        } catch (AlreadySubscribedException $ase) {
            $titulo = 'ERROR';
            $answer = $ase->getMessage();
            $data = [$titulo, $answer];
        }

        return $data;
    }
}
