<?php

declare(strict_types=1);

namespace  App\Manager;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PollRepository;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class PollManager
{
    private $pollRepository;
    private $commentRepository;
    private $logger;

    public function __construct(PollRepository $pollRepository, CommentRepository $commentRepository, LoggerInterface $logger)
    {
        $this->pollRepository = $pollRepository;
        $this->commentRepository = $commentRepository;
        $this->logger = $logger;
    }

    public function getPollById($id)
    {
        return $this->pollRepository->getPollById($id);
    }

    public function getPollsOrderBy($criteria, $order, $limit, $offset)
    {
         return $this->pollRepository->findBy($criteria, $order, $limit, $offset);
    }

    public function getTotalPolls()
    {
        return $this->pollRepository->contarPolls();
    }

    public function saveComment($text, $id)
    {
        $poll = $this->pollRepository->find($id);

        $comment = new Comment();
        $comment->setPoll($poll);
        $comment->setText($text);

        $poll->addComment($comment);

        try {
            $this->commentRepository->saveComment($comment);

            return true;
        } catch (ORMException $error) {
            $this->logger->alert($error->getMessage());

            return false;
        }
    }
}
