<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('avatar', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'formulario.mimeThumbnail',
                    ])
                ],
            ])
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.editarUsuario', 'attr' => ['class' => 'btn'])
            );
    }
}
