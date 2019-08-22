<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 30/05/18
 * Time: 10:37.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CorreccionExamenController extends AbstractController
{
    /**
     * @Route ("/correccion"), name="correccion"
     */
    public function indexAction()
    {
        return $this->render('examCorrection/basic.html.twig');
    }

    /**
     * @Route ("/styles"), name="styles"
     */
    public function stylesAction()
    {
        return $this->render('examCorrection/styles.html.twig');
    }
}
