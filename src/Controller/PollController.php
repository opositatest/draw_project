<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Poll;
use App\Entity\Question;
use App\Forms\AddQuestionType;
use App\Manager\PollManager;
use App\Manager\QuestionManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PollController extends BaseController
{
    const NUM_ENCUESTAS_INDEX = 4;
    const NUM_ENCUESTAS_INDEX_HOME = 4;
    private $offset = 0;

    /**
     * @Route("/poll/{id}", name="poll")
     */
    public function indexAction($id, PollManager $pollManager)
    {
        $poll = $pollManager->getPollById($id);
        if(!$poll){
            $error = "No hay ninguna poll con ese ID";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }

        $jsonContent = $this->serializar($poll);

        return $this->render('poll/poll.html.twig', [
            'poll' => $jsonContent,
        ]);
    }

    /**
     * @Route ("/poll/comment/save", name="comment")
     */
    public function saveCommentAction(Request $request, PollManager $pollManager)
    {
        $texto = $request->get('texto');
        $poll = $request->get('poll');

        $saved = $pollManager->saveComment($texto, $poll);

        if ($saved) {
            return new Response();
        }
        $answer = 'Ha habido un error al añadir su comment. Lo sentimos.';

        return new Response($answer);
    }

    /**
     * @Route ("/home", name="home")
     */
    public function homeAction(PollManager $pollManager)
    {
        $offset = 0;

        $polls = $pollManager->getPollsOrderBy([], ['id' => 'DESC'], self::NUM_ENCUESTAS_INDEX_HOME, $offset);

        return $this->render('poll/home.html.twig', ['polls' => $polls]);
    }

    /**
     * @Route ("/polls", name="polls")
     */
    public function showPollsAction(Request $request, PollManager $pollManager)
    {
        /** @var Poll $ultima */
        $ultima = $pollManager->getPollsOrderby([], ['id' => 'DESC'], 1, 0);
        if(!$ultima){
            $error = "No se encontro ninguna poll";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $ultima = $ultima[0];
        /** @var Poll $primera */
        $primera = $pollManager->getPollsOrderby([], ['id' => 'ASC'], 1, 0);
        if(!$primera){
            $error = "No se encontro ninguna poll";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $primera = $primera[0];

        $polls = $pollManager->getPollsOrderby(
            [],
            ['id' => 'DESC'],
            self::NUM_ENCUESTAS_INDEX,
            $this->offset
        );
        if(!$polls) {
            $error = "No se encontro ninguna poll";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $num = $pollManager->getTotalPolls()[0]['1'];

        return $this->render('poll/show_poll.html.twig', ['historial' => $polls,
            'offset' => $this->offset, 'ultimo' => $ultima->getId(), 'primero' => $primera->getId(), 'total' => $num, ]);
    }

    /**
     * @Route ("/polls/next-prev", name="pollsN")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function paginationAction(Request $request, PollManager $pollManager)
    {
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        if ('next' === $op) {
            $offset += self::NUM_ENCUESTAS_INDEX;
        } elseif ('prev' === $op) {
            $offset -= self::NUM_ENCUESTAS_INDEX;
        }

        $polls = $pollManager->getPollsOrderby([], ['id' => 'DESC'], self::NUM_ENCUESTAS_INDEX, $offset);

        $jsonContent = $this->serializar($polls);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }

    /**
     * @Route("/form", name="add_question")
     */
    public function FormTest(Request $request, QuestionManager $questionManager){
        $question = new Question();

        $form = $this->createForm(AddQuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $questionManager->addQuestion($data);
        }

        return $this->render('poll/form.html.twig', ['form' => $form->createView()]);
    }
}
