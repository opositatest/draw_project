<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RespuestaType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class, ['label' => 'Pregunta'])
            ->add('value', NumberType::class, ['label' => 'Valor']);
    }
}