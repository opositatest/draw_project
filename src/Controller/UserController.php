<?php

namespace App\Controller;

use App\Entity\Sorteo;
use App\Entity\Usuario;
use App\Forms\LoginType;
use App\Manager\EncuestaManager;
use App\Manager\SorteoManager;
use App\Manager\UsuarioManager;
use App\Services\UsuarioService;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController{
    /**
     * @Route("/sorteo/login", name="login")
     */
    public function loginAction(Request $request, UsuarioManager $usuarioManager, EncuestaManager $encuestaManager, SorteoManager $sorteoManager){
        $user = new Usuario();

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            /** @var Usuario $usuario */
            $usuario = $usuarioManager->getOneUsuarioBy(array('email' => $user->getEmail()));

            if($usuario) {
                $sorteos = $usuario->getSorteos();
                $ganados = $usuario->getSorteosGanados();
                $hash = $usuario->getPassword();
                $encuesta = $encuestaManager->getEncuestasOrderBy(array(), array('id' => 'ASC'), 1, 0);

                $actual = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

                /** @var Sorteo $sort_actual */
                $sort_actual = $actual[0];

                if (password_verify($user->getPassword(), $hash)){
                    return $this->render('encuesta/comprobarSorteo.html.twig', array('usuario' => $usuario, 'sorteos' => $sorteos,
                        'ganados' => $ganados, 'encuesta' => $encuesta, 'id_actual' => $sort_actual->getId()));
                } else {
                    return $this->render('encuesta/login.html.twig', array(
                        'form' => $form->createView(),
                        'errorc' => "Contraseña incorrecta",
                    ));
                }
            } else {
                return $this->render('encuesta/login.html.twig', array(
                    'form' => $form->createView(),
                    'erroru' => "Usuario incorrecto",
                ));
            }
        }

        return $this->render('encuesta/login.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route ("/sorteo/leave", name="borrar")
     */
    public function borrarUserAction(Request $request, SorteoManager $sorteoManager) {
        //get data from ajax
        $mail = $request->get('mail');
        $pass = $request->get('pass');
        $userData = [$mail, $pass];

        $sort = $sorteoManager->getSorteosOrderby(array(), array('fecha' => 'DESC'), 1, 0);

        /** @var Sorteo $actual */
        $actual = $sort[0];
        $num = 0;

        $result = $usuarioService->borrarUser($userData, $actual, $num);

        return new JsonResponse($result);
    }
}