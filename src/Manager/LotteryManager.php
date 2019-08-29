<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Prize;
use App\Entity\Lottery;
use App\Entity\User;
use App\Exceptions\GanadorNotSettedException;
use App\Repository\PrizeRepository;
use App\Repository\LotteryRepository;
use DateInterval;
use Exception;
use Psr\Log\LoggerInterface;

class LotteryManager
{
    private $lotteryRepository;
    private $prizeRepository;
    private $logger;

    public function __construct(LotteryRepository $lotteryRepository, PrizeRepository $prizeRepository, LoggerInterface $logger)
    {
        $this->lotteryRepository = $lotteryRepository;
        $this->prizeRepository = $prizeRepository;
        $this->logger = $logger;
    }

    public function getLotteriesOrderBy($criteria, $order, $limit, $offset)
    {
        return $this->lotteryRepository->findLotteryOrderBy($criteria, $order, $limit, $offset);
    }

    public function getLotteriesBetween($min, $max)
    {
        return $this->lotteryRepository->findBetween($min, $max);
    }

    public function contarLotteries()
    {
        return $this->lotteryRepository->contarLotteries();
    }

    public function addLottery($user, $lottery){

        $this->lotteryRepository->addLottery($lottery);
    }

    public function runLottery($lottery_actual): void
    {
        $users_lottery = $lottery_actual->getUsers();

        try {
            if (\count($users_lottery) > 0) {
                $random = random_int(0, \count($users_lottery) - 1);
                /** @var User $ganador */
                $ganador = $users_lottery[$random];

                $lottery_actual->setGanador($ganador);
                $this->lotteryRepository->saveLottery($lottery_actual);
            } elseif (0 === \count($users_lottery)) {
                $this->logger->info('No hay users.');
            }
        } catch (GanadorNotSettedException $gnse) {
            $this->logger->alert($gnse->getMessage());
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    public function crearLottery($fechaLottery)
    {
        try {
            $today = new \DateTimeImmutable();
            $newFecha = $today->add(new DateInterval('P1M'));

            $prizes = $this->prizeRepository->getAllPrizes();
            /** @var Prize $randomPrize */
            $randomPrize = $prizes[random_int(0, \count($prizes) - 1)];

            $newLottery = new Lottery();
            $newLottery->setPrize($randomPrize);
            $newLottery->setImg($randomPrize->getImagen());
            $newLottery->setFecha($newFecha);

            $this->lotteryRepository->addLottery($newLottery);

            return $newLottery;
        } catch (Exception $e) {
            $this->logger->alert($e->getMessage());
        }
    }
}
