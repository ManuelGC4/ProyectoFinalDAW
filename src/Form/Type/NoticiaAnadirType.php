<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class NoticiaAnadirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titular', TextType::class, array('label' => 'formulario.titular'))
            ->add('entradilla', TextareaType::class, array('label' => 'formulario.entradilla', 'required' => false))
            ->add('cuerpo', TextareaType::class, array('label' => 'formulario.cuerpo', 'required' => false))
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.añadirNoticia')
            );
    }
}
