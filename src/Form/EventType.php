<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roundMinutes = "10";
        $roundSeconds = "20";
        $pauseMinutes = "30";
        $pauseSeconds = "50";

        $currentDate = new \DateTime();

        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date', DateTimeType::class, array(
                'data' => $currentDate))
            ->add('round_minutes', IntegerType::class, array(
                'required' => false,
                'empty_data' => $roundMinutes))
            ->add('round_seconds', IntegerType::class, array(
                'required' => false,
                'empty_data' => $roundSeconds))
            ->add('pause_minutes', IntegerType::class, array(
                'required' => false,
                'empty_data' => $pauseMinutes))
            ->add('pause_seconds', IntegerType::class, array(
                'required' => false,
                'empty_data' => $pauseSeconds))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
