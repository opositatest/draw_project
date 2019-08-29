<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Lottery;
use App\Entity\User;
use App\Forms\LoginType;
use App\Forms\RegisterType;
use App\Manager\PollManager;
use App\Manager\LotteryManager;
use App\Manager\UserManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends BaseController
{

    /**
     * @Route("/lottery/registro", name="register")
     */
    public function register(Request $request, UserManager $userManager)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userManager->newUser($user);

            return $this->redirectToRoute('home');
        }

        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/lottery/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

}
