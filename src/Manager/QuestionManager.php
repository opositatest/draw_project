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
        $answers = $question->getAnswers();
        foreach($answers as $answer){
            $newAnswer = new Answer();
            $newAnswer->setQuestion($question);
            $newAnswer->setText($answer["text"]);
            $newAnswer->setValue($answer["value"]);

            $answer = $newAnswer;
        }
        dump($answers);
        $this->questionRepository->addQuestion($question);
    }
}