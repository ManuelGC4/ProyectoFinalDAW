<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TemaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'claro',
                SubmitType::class,
                array('label' => 'base.claro', 'attr' => ['class' => 'btn'])
            )
            ->add(
                'oscuro',
                SubmitType::class,
                array('label' => 'base.oscuro', 'attr' => ['class' => 'btn'])
            );
    }
}
