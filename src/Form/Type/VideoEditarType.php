<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class VideoEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, array('attr' => ['class' => 'form-control']))
            ->add('descripcion', TextareaType::class, array('attr' => ['class' => 'form-control'], 'required' => false))
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.editar', 'attr' => ['class' => 'btn'])
            );
    }
}
