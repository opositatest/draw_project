<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Encuesta;
use App\Entity\Pregunta;
use App\Forms\AddPreguntaType;
use App\Manager\EncuestaManager;
use App\Manager\QuestionManager;
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
        if(!$encuesta){
            $error = "No hay ninguna encuesta con ese ID";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }

        $jsonContent = $this->serializar($encuesta);

        return $this->render('encuesta/encuesta.html.twig', [
            'encuesta' => $jsonContent,
        ]);
    }

    /**
     * @Route ("/encuesta/comment/save", name="comentario")
     */
    public function saveCommentAction(Request $request, EncuestaManager $encuestaManager)
    {
        $texto = $request->get('texto');
        $encuesta = $request->get('encuesta');

        $saved = $encuestaManager->saveComment($texto, $encuesta);

        if ($saved) {
            return new Response();
        }
        $respuesta = 'Ha habido un error al añadir su comentario. Lo sentimos.';

        return new Response($respuesta);
    }

    /**
     * @Route ("/home", name="home")
     */
    public function homeAction(EncuestaManager $encuestaManager)
    {
        $offset = 0;

        $encuestas = $encuestaManager->getEncuestasOrderBy([], ['id' => 'DESC'], self::NUM_ENCUESTAS_INDEX_HOME, $offset);

        return $this->render('encuesta/home.html.twig', ['encuestas' => $encuestas]);
    }

    /**
     * @Route ("/encuestas", name="encuestas")
     */
    public function showEncuestasAction(Request $request, EncuestaManager $encuestaManager)
    {
        /** @var Encuesta $ultima */
        $ultima = $encuestaManager->getEncuestasOrderby([], ['id' => 'DESC'], 1, 0);
        if(!$ultima){
            $error = "No se encontro ninguna encuesta";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $ultima = $ultima[0];
        /** @var Encuesta $primera */
        $primera = $encuestaManager->getEncuestasOrderby([], ['id' => 'ASC'], 1, 0);
        if(!$primera){
            $error = "No se encontro ninguna encuesta";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $primera = $primera[0];

        $encuestas = $encuestaManager->getEncuestasOrderby(
            [],
            ['id' => 'DESC'],
            self::NUM_ENCUESTAS_INDEX,
            $this->offset
        );
        if(!$encuestas) {
            $error = "No se encontro ninguna encuesta";
            return $this->render('encuesta/no_encuesta.html.twig', ['error' => $error]);
        }
        $num = $encuestaManager->getTotalEncuestas()[0]['1'];

        return $this->render('encuesta/mostrarEncuestas.html.twig', ['historial' => $encuestas,
            'offset' => $this->offset, 'ultimo' => $ultima->getId(), 'primero' => $primera->getId(), 'total' => $num, ]);
    }

    /**
     * @Route ("/encuestas/next-prev", name="encuestasN")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function paginationAction(Request $request, EncuestaManager $encuestaManager)
    {
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        if ('next' === $op) {
            $offset += self::NUM_ENCUESTAS_INDEX;
        } elseif ('prev' === $op) {
            $offset -= self::NUM_ENCUESTAS_INDEX;
        }

        $encuestas = $encuestaManager->getEncuestasOrderby([], ['id' => 'DESC'], self::NUM_ENCUESTAS_INDEX, $offset);

        $jsonContent = $this->serializar($encuestas);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }

    /**
     * @Route("/form", name="add_pregunta")
     */
    public function FormTest(Request $request, QuestionManager $questionManager){
        $pregunta = new Pregunta();

        $form = $this->createForm(AddPreguntaType::class, $pregunta);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $questionManager->addQuestion($data);
        }

        return $this->render('encuesta/form.html.twig', ['form' => $form->createView()]);
    }
}
