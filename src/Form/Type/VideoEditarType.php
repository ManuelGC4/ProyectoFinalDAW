<?php

namespace App\Form\Type;

use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VideoEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, array('attr' => ['class' => 'form-control']))
            ->add('descripcion', TextareaType::class, array('attr' => ['class' => 'form-control'], 'required' => false))
            ->add('thumbnail', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'formulario.mimeThumbnail',
                    ])
                ],
            ])
            ->add('video', FileType::class, ['mapped' => false, 'required' => false, 'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypes' => [
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => 'formulario.mimeThumbnail',
                    ])
                ],
            ])
            ->add('categoria', EntityType::class, ['class' => Categoria::class, 'choice_label' => 'nombre'])
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'formulario.editar', 'attr' => ['class' => 'btn'])
            );
    }
}
