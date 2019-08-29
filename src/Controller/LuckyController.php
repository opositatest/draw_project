<?php

declare(strict_types=1);

// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    /**
     * @Route("/pizarra", name="example")
     */
    public function number()
    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $poll = $entityManager->getRepository(Poll::class)->find($id);

        return $this->render(
            'basic.html'
        );
    }
}
