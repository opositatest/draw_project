<?php

namespace App\Forms;

use App\Entity\Encuesta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddPreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('respuestas', CollectionType::class, [
                'entry_type' => RespuestaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false
                ])
            ->add('text', TextType::class, ['label' => 'Pregunta'])
            ->add('image', TextType::class, ['label' => 'Nombre Imagen'])
            ->add('encuesta', EntityType::class, ['class' => Encuesta::class]);
    }
}