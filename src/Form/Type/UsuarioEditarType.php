<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nick', TextType::class, array('attr' => ['class' => 'form-control']))
            ->add('nombre', TextType::class, array('attr' => ['class' => 'form-control']))
            ->add('apellidos', TextType::class, array('attr' => ['class' => 'form-control']))
            ->add('email', EmailType::class, array('attr' => ['class' => 'form-control']))
            ->add('password', PasswordType::class, array('attr' => ['class' => 'form-control']))
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.editarUsuario', 'attr' => ['class' => 'btn'])
            );
    }
}
