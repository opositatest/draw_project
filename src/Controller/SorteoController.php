<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Entity\Sorteo;
use App\Manager\SorteoManager;
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

        $last4 = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), self::NUM_SORTEOS_INDEX, $this->offset);
        $actual = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0)[0];
        $ultimo = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 1)[0];
        $primero = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'ASC'), 1, 0)[0];
        $total = $sorteoManager->contarSorteos();
        $size = $total[0]['1'] - 1;


        return $this->render('sorteo/sorteo.html.twig', array('historial' => $last4, 'actual' => $actual,
            'offset' => $this->offset, 'ultimo' => $ultimo->getId(), 'primero' => $primero->getId(), 'total' => $size));
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


        $sorteo = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $sorteoActual */
        $sorteoActual = $sorteo[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new DateTime();

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

        if ($op == 'next') {
            $offset += self::NUM_SORTEOS_INDEX;
        } elseif ($op == 'prev')
            $offset -= self::NUM_SORTEOS_INDEX;

        $show_sorteos = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), self::NUM_SORTEOS_INDEX, $offset);

        $jsonContent = $this->serializar($show_sorteos);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }


    /**
     * @route ("/sorteo/area-personal", name="profile")
     */
    public function comprobarSorteoAction()
    {
        return $this->render('sorteo/comprobarSorteo.html.twig');
    }


}
