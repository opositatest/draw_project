<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Forms\LoginType;
use App\Manager\EncuestaManager;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * @Route("/sorteo/login", name="login")
     */
    public function loginAction(Request $request, UsuarioManager $usuarioManager)
    {
        $user = new Usuario();

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            /** @var Usuario $usuario */
            $usuario = $usuarioManager->getOneUsuarioBy(['email' => $user->getEmail()]);

            if ($usuario) {
                $hash = $usuario->getPassword();

                /** @var Sorteo $sort_actual */

                if (password_verify($user->getPassword(), $hash)) {
                    return $this->redirectToRoute("show-sorteo", ["id" => $usuario->getId()]);
                }
                $form->get('password')->addError(new FormError("Contraseña incorrecta"));

                return $this->render('user/login.html.twig', [
                        'form' => $form->createView()
                    ]);
            }
            $form->get('email')->addError(new FormError("Ese email no esta registrado!"));

            return $this->render('user/login.html.twig', [
                    'form' => $form->createView()
                ]);
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route ("/sorteo/leave", name="borrar")
     */
    public function borrarUserAction(Request $request, SorteoManager $sorteoManager, UsuarioManager $usuarioManager)
    {
        //get data from ajax
        $mail = $request->get('mail');
        $pass = $request->get('pass');
        $userData = [$mail, $pass];

        $sort = $sorteoManager->getSorteosOrderby([], ['fecha' => 'DESC'], 1, 0);

        /** @var Sorteo $actual */
        $actual = $sort[0];
        $num = 0;

        $result = $usuarioManager->borrarUser($userData, $actual, $num);

        return new JsonResponse($result);
    }
}
