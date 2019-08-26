<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorteo;
use App\Manager\EncuestaManager;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use App\Services\SorteoService;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SorteoController extends BaseController
{
    const NUM_SORTEOS_INDEX = 3;
    private $offset = 1;

    /**
     * @Route ("/sorteo", name="sorteo")
     */
    public function sorteoAction(SorteoManager $sorteoManager)
    {
        $last4 = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], self::NUM_SORTEOS_INDEX, $this->offset);
        $actual = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], 1, 0);
        if(!$actual){
            $error = "no hay ningun sorteo actualmente";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $actual = $actual[0];

        $ultimo = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], 1, 1);
        if(!$ultimo){
            $error = "No hay ningun sorteo ahora mismo";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $ultimo = $ultimo[0];

        $primero = $sorteoManager->getSorteosOrderby([], ['fecha' => 'ASC'], 1, 0);
        if (!$primero){
            $error = "no hay ningun sorteo ahora mismo";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $primero = $primero[0];
        $total = $sorteoManager->contarSorteos();
        $size = $total[0]['1'] - 1;

        return $this->render('sorteo/sorteo.html.twig', ['historial' => $last4, 'actual' => $actual,
            'offset' => $this->offset, 'ultimo' => $ultimo->getId(), 'primero' => $primero->getId(), 'total' => $size, ]);
    }

    /**
     * @Route ("/sorteo/add", name="subscription")
     */
    public function subsciptionAction(Request $request, SorteoManager $sorteoManager, SorteoService $sorteoService)
    {
        //get data from ajax
        $name = $request->get('name');
        $mail = $request->get('mail');
        $pass = $request->get('pass');

        $userData = [$name, $mail, $pass];

        $sorteo = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], 1, 0);

        /** @var Sorteo $sorteoActual */
        $sorteoActual = $sorteo[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new \DateTimeImmutable();

        // fecha sorteo_actual
        $fechaSorteo = $sorteoActual->getFecha();

        $result = $sorteoService->sorteoManagerAction($userData, $date, $fechaSorteo, $sorteoActual);

        return new JsonResponse($result);
    }

    /**
     * @Route ("/sorteo/historial", name="historial")
     */
    public function historialAction(Request $request, SorteoManager $sorteoManager)
    {
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        if ('next' === $op) {
            $offset += self::NUM_SORTEOS_INDEX;
        } elseif ('prev' === $op) {
            $offset -= self::NUM_SORTEOS_INDEX;
        }

        $show_sorteos = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], self::NUM_SORTEOS_INDEX, $offset);

        $jsonContent = $this->serializar($show_sorteos);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }

    /**
     * @Route("/sorteo/user/{id}", name="show-sorteo")
     */
    public function showSorteo(Request $request, $id, UsuarioManager $usuarioManager ,EncuestaManager $encuestaManager, SorteoManager $sorteoManager)
    {
        $user = $usuarioManager->getOneUsuarioBy(["id" => $id]);
        if(!$user){
            $error = "No hay ningun usuario con ese ID";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }

        $sorteos = $user->getSorteos();
        if(!$sorteos){
            $error = "No hay ningun Sorteo Actualmente";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $ganados = $user->getSorteosGanados();
        $encuesta = $encuestaManager->getEncuestasOrderBy([], ['id' => 'ASC'], 1, 0);
        $actual = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], 1, 0);
        if(!$actual){
            $error = "No hay ningun Sorteo Actualmente";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $sort_actual = $actual[0];

        return $this->render('sorteo/comprobarSorteo.html.twig', ['usuario' => $user, 'sorteos' => $sorteos,
            'ganados' => $ganados, 'encuesta' => $this->serializar($encuesta[0]), 'id_actual' => $sort_actual->getId(), ]);
    }
}
