<?php

namespace App\Manager;

use App\Entity\Pregunta;
use App\Entity\Respuesta;
use App\Repository\PreguntaRepository;

class QuestionManager{
    private $questionRepository;
    public function __construct(PreguntaRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function addQuestion(Pregunta $question)
    {
        $respuestas = $question->getRespuestas();
        foreach($respuestas as $respuesta){
            $newRespuesta = new Respuesta();
            $newRespuesta->setPregunta($question);
            $newRespuesta->setText($respuesta["text"]);
            $newRespuesta->setValue($respuesta["value"]);

            $respuesta = $newRespuesta;
        }
        dump($respuestas);
        $this->questionRepository->addQuestion($question);
    }
}