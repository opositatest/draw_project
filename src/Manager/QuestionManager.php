<?php

namespace App\Manager;

use App\Entity\Question;
use App\Entity\Answer;
use App\Repository\QuestionRepository;

class QuestionManager{
    private $questionRepository;
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function addQuestion(Question $question)
    {
        $this->questionRepository->addQuestion($question);
    }
}