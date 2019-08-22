<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Manager\EncuestaManager;
use App\Services\EncuestaService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EncuestaController extends BaseController
{
    const NUM_ENCUESTAS_INDEX = 4;
    const NUM_ENCUESTAS_INDEX_HOME = 4;
    private $offset = 0;

    /**
     * @Route("/encuesta/{id}", name="encuesta")
     */
    public function indexAction($id, EncuestaManager $encuestaManager)
    {
        $encuesta = $encuestaManager->getEncuestaById($id);

        $jsonContent = $this->serializar($encuesta);

        return $this->render('encuesta/encuesta.html.twig', array(
            'encuesta' => $jsonContent
        ));
    }

    /**
     * @Route ("/encuesta/comment/save", name="comentario")
     */
    public function saveCommentAction(Request $request, EncuestaManager $encuestaManager){
        $texto = $request->get('texto');
        $encuesta = $request->get('encuesta');

        $saved = $encuestaManager->saveComment($texto, $encuesta);

        if ($saved){
            return new Response();
        } else {
            $respuesta = "Ha habido un error al añadir su comentario. Lo sentimos.";
            return new Response($respuesta);
        }
    }

    /**
     * @Route ("/home", name="home")
     */
    public function homeAction(EncuestaManager $encuestaManager){
        $offset = 0;

        $encuestas = $encuestaManager->getEncuestasOrderBy(array(), array('id' => 'DESC'), self::NUM_ENCUESTAS_INDEX_HOME, $offset);

        return $this->render('encuesta/home.html.twig', array('encuestas' => $encuestas));
    }

    /**
     * @Route ("/encuestas", name="encuestas")
     */
    public function showEncuestasAction(Request $request, EncuestaManager $encuestaManager){
        /** @var Encuesta $ultima */
        $ultima = $encuestaManager->getEncuestasOrderby(array(), array('id' => 'DESC'), 1, 0)[0];
        /** @var Encuesta $primera */
        $primera = $encuestaManager->getEncuestasOrderby(array(), array('id' => 'ASC'), 1, 0)[0];

        $encuestas = $encuestaManager->getEncuestasOrderby(array(), array('id' => 'DESC'),
            self::NUM_ENCUESTAS_INDEX, $this->offset);

        $num = $encuestaManager->getTotalEncuestas()[0]['1'];

        return $this->render('encuesta/mostrarEncuestas.html.twig', array('historial' => $encuestas,
            'offset' => $this->offset, 'ultimo' => $ultima->getId(), 'primero' => $primera->getId(), 'total' => $num));
    }

    /**
     * @Route ("/encuestas/next-prev", name="encuestasN")
     * @param Request $request
     * @return Response
     */
    public function paginationAction(Request $request, EncuestaManager $encuestaManager){
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        if ($op == 'next'){
            $offset += self::NUM_ENCUESTAS_INDEX;
        } elseif ($op == 'prev')
            $offset -= self::NUM_ENCUESTAS_INDEX;

        $encuestas = $encuestaManager->getEncuestasOrderby(array(), array('id' => 'DESC'), self::NUM_ENCUESTAS_INDEX, $offset);

        $jsonContent = $this->serializar($encuestas);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }
}
