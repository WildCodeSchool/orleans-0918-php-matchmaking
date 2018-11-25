<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Constraints;

class FormatEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                Type\TextType::class,
                [
                    'label' => 'Nom du format',
                    'attr' => ['placeholder' => 'ex: 9 players', 'maxlength' => 255],
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3, 'max' => 255])
                        ]
                ]
            )
            ->add(
                'numberOfTables',
                Type\IntegerType::class,
                [
                    'label' => 'Nombre de table',
                    'attr' => ['value' => 3, 'min' => 3],
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Type('integer')
                    ]
                ]
            )
            ->add(
                'csvFile',
                Type\FileType::class,
                [
                    'label' => 'Fichier d\'import CSV',
                    'attr' => ['accept' => 'text/csv'],
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\File(['maxSize' => '1024k', 'mimeTypes' => ['text/csv', 'text/plain']])
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
