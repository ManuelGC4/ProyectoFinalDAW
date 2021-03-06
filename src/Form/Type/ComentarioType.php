<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ComentarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texto', TextareaType::class, array('required' => false, 'attr' => ['class' => 'form-control']))
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.añadirComentario', 'attr' => ['class' => 'btn'])
            );
    }
}
