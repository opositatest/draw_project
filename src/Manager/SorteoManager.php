<?php

namespace App\Manager;

use App\Entity\Premio;
use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Exceptions\GanadorNotSettedException;
use App\Repository\PremioRepository;
use App\Repository\SorteoRepository;
use DateInterval;
use Exception;
use Psr\Log\LoggerInterface;

class SorteoManager{
    private $sorteoRepository;
    private $premioRepository;
    private $logger;

    public function __construct(SorteoRepository $sorteoRepository, PremioRepository $premioRepository, LoggerInterface $logger)
    {
        $this->sorteoRepository = $sorteoRepository;
        $this->premioRepository = $premioRepository;
        $this->logger = $logger;
    }

    public function getSorteosOrderBy($criteria, $order, $limit, $offset)
    {
        return $this->sorteoRepository->findSorteoOrderBy($criteria,$order,$limit,$offset);
    }

    public function getSorteosBetween($min, $max)
    {
        return $this->sorteoRepository->findBetween($min, $max);
    }

    public function contarSorteos()
    {
        return $this->sorteoRepository->contarSorteos();
    }

    public function runSorteo($sorteo_actual)
    {
        $usuarios_sorteo = $sorteo_actual->getUsuarios();
        try{
            if (count($usuarios_sorteo) > 0) {
                if (count($usuarios_sorteo) > 0){
                    $random = rand(0, count($usuarios_sorteo) - 1);
                    /** @var Usuario $ganador */
                    $ganador = $usuarios_sorteo[$random];

                    $sorteo_actual->setGanador($ganador);

                    $this->sorteoRepository->finishSorteo($sorteo_actual);
                }
            } else if (count($usuarios_sorteo) == 0){
                $this->logger->info('No hay usuarios.');
            }
        }catch (GanadorNotSettedException $gnse) {
            $this->logger->alert($gnse->getMessage());
        }catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    public function crearSorteo($fechaSorteo)
    {
        try {
            $today = new \DateTime();
            $newFecha = $today->add(new DateInterval('P1M'));

            $premios = $this->premioRepository->getAllPremios();
            /** @var Premio $randomPremio */
            $randomPremio = $premios[rand(0, count($premios) - 1)];

            $newSorteo = new Sorteo();
            $newSorteo->setPremio($randomPremio);
            $newSorteo->setImg($randomPremio->getImagen());
            $newSorteo->setFecha($newFecha);

            $this->sorteoRepository->addSorteo($newSorteo);

            return $newSorteo;
        } catch (Exception $e) {
            $this->logger->alert($e->getMessage());
        }
    }
}