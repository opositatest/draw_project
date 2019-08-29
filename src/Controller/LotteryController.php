<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Lottery;
use App\Manager\PollManager;
use App\Manager\LotteryManager;
use App\Manager\UserManager;
use App\Services\LotteryService;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LotteryController extends BaseController
{
    const NUM_SORTEOS_INDEX = 3;
    private $offset = 1;

    /**
     * @Route ("/lottery", name="lottery")
     */
    public function lotteryAction(LotteryManager $lotteryManager)
    {

        $last4 = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], self::NUM_SORTEOS_INDEX, $this->offset);
        $actual = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], 1, 0);
        if(!$actual){
            $error = "no hay ningun lottery actualmente";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $actual = $actual[0];

        $ultimo = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], 1, 1);
        if(!$ultimo){
            $error = "No hay ningun lottery ahora mismo";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $ultimo = $ultimo[0];

        $primero = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'ASC'], 1, 0);
        if (!$primero){
            $error = "no hay ningun lottery ahora mismo";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $primero = $primero[0];
        $total = $lotteryManager->contarLotteries();
        $size = $total[0]['1'] - 1;

        return $this->render('lottery/lottery.html.twig', ['historial' => $last4, 'actual' => $actual,
            'offset' => $this->offset, 'ultimo' => $ultimo->getId(), 'primero' => $primero->getId(), 'total' => $size, ]);
    }

    /**
     * @Route ("/lottery/add", name="subscription")
     */
    public function subsciptionAction(Request $request, LotteryManager $lotteryManager, LotteryService $lotteryService)
    {
       $user = $this->getUser();

        $lottery = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], 1, 0);

        /** @var Lottery $lotteryActual */
        $lotteryActual = $lottery[0];

        // fecha de hoy
        /** @var datetime $date */
        $date = new \DateTimeImmutable();

        // fecha lottery_actual
        $fechaLottery = $lotteryActual->getFecha();

        $result = $lotteryService->lotteryManagerAction($user, $date, $fechaLottery, $lotteryActual);

        $this->addFlash('notice', $result[0].' '.$result[1]);


        return $this->redirectToRoute('lottery');
    }

    /**
     * @Route ("/lottery/historial", name="historial")
     */
    public function historialAction(Request $request, LotteryManager $lotteryManager)
    {
        $op = $request->query->get('operation');
        $offset = $request->query->get('offset');

        if ('next' === $op) {
            $offset += self::NUM_SORTEOS_INDEX;
        } elseif ('prev' === $op) {
            $offset -= self::NUM_SORTEOS_INDEX;
        }

        $show_lotteries = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], self::NUM_SORTEOS_INDEX, $offset);

        $jsonContent = $this->serializar($show_lotteries);

        $data = [$jsonContent, $offset];

        return new JsonResponse($data);
    }

    /**
     * @Route("/lottery/user", name="show-lottery")
     */
    public function showLottery(Request $request, UserManager $userManager ,PollManager $pollManager, LotteryManager $lotteryManager)
    {
        $user = $this->getUser();
        if(!$user){
            $error = "No hay ningun user con ese ID";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }

        $lotteries = $user->getLotteries();
        if(!$lotteries){
            $error = "No hay ningun Lottery Actualmente";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $ganados = $user->getLotteriesGanados();
        $poll = $pollManager->getPollsOrderBy([], ['id' => 'ASC'], 1, 0);
        $actual = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], 1, 0);
        if(!$actual){
            $error = "No hay ningun Lottery Actualmente";
            return $this->render('poll/no_poll.html.twig', ['error' => $error]);
        }
        $lotteryActual = $actual[0];

        return $this->render('lottery/user_lottery.html.twig', ['user' => $user, 'lotteries' => $lotteries,
            'ganados' => $ganados, 'poll' => $this->serializar($poll[0]), 'id_actual' => $lotteryActual->getId(), ]);
    }

    /**
     * @Route ("/lottery/leave", name="borrar")
     */
    public function borrarUserAction(Request $request, LotteryManager $lotteryManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $sort = $lotteryManager->getLotteriesOrderby([], ['fecha' => 'DESC'], 1, 0);

        /** @var Lottery $actual */
        $actual = $sort[0];
        $num = 0;

        $userManager->borrarUserFromLottery($user, $actual, $num);

        return $this->redirectToRoute('lottery');

    }
}
