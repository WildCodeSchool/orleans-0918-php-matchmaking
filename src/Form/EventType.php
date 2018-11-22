<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $round_minutes = "10";
        $round_seconds = "20";
        $pause_minutes = "30";
        $pause_seconds = "50";

        $currentDate = new \DateTime();

        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date', DateTimeType::class, array(
                'data' => $currentDate ) )
            ->add('round_minutes', IntegerType::class, array(
                'required' => false,
                'empty_data' => $round_minutes ) 
                )
            ->add('round_seconds', IntegerType::class, array(
                'required' => false,
                'empty_data' => $round_seconds ) 
                )
            ->add('pause_minutes', IntegerType::class, array(
                'required' => false,
                'empty_data' => $pause_minutes ) 
                )
            ->add('pause_seconds', IntegerType::class, array(
                'required' => false,
                'empty_data' => $pause_seconds ) 
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
