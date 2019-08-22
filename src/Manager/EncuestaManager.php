<?php

declare(strict_types=1);

namespace  App\Manager;

use App\Entity\Comentario;
use App\Repository\ComentarioRepository;
use App\Repository\EncuestaRepository;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class EncuestaManager
{
    private $encuestaRepository;
    private $comentarioRepository;
    private $logger;

    public function __construct(EncuestaRepository $encuestaRepository, ComentarioRepository $comentarioRepository, LoggerInterface $logger)
    {
        $this->encuestaRepository = $encuestaRepository;
        $this->comentarioRepository = $comentarioRepository;
        $this->logger = $logger;
    }

    public function getEncuestaById($id)
    {
        return $this->encuestaRepository->getEncuestaById($id);
    }

    public function getEncuestasOrderBy($criteria, $order, $limit, $offset)
    {
        return $this->encuestaRepository->findBy($criteria, $order, $limit, $offset);
    }

    public function getTotalEncuestas()
    {
        return $this->encuestaRepository->contarEncuestas();
    }

    public function saveComment($text, $id)
    {
        $encuesta = $this->encuestaRepository->find($id);

        $comment = new Comentario();
        $comment->setEncuesta($encuesta);
        $comment->setText($text);

        try {
            $this->comentarioRepository->addComentario($comment);

            return true;
        } catch (ORMException $error) {
            $this->logger->alert($error->getMessage());

            return false;
        }
    }
}
