<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\FormatEvent;
use App\Entity\Society;
use App\Entity\User;
use App\Entity\StatusEvent;
use function Deployer\add;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class)
            ->add('society', EntityType::class, [
                'class' => Society::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('statusEvent', EntityType::class, [
                'class' => StatusEvent::class,
                'choice_label' => 'name',
                'choice_attr' => function ($key) use ($options) {
                    $disabled = true;

                    if ($key->getState() == $options['status'] || ($options['status'] < 2 && $key->getState() < 2)) {
                        $disabled = false;
                    }

                    return $disabled ? ['disabled' => 'disabled'] : [];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.state', 'ASC');
                }
            ])
            ->add('description', TextareaType::class)
            ->add('date', DateTimeType::class)
            ->add('formatEvent', EntityType::class, [
                'class' => FormatEvent::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.numberOfPlayers', 'ASC');
                }
            ])
            ->add('logoFile', FileType::class, [
                'required' => false
            ])
            ->add('roundMinutes', IntegerType::class)
            ->add('roundSeconds', IntegerType::class)
            ->add('pauseMinutes', IntegerType::class)
            ->add('pauseSeconds', IntegerType::class);
//            ->add('users', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'userInfos',
//                'by_reference' => false,
//                'expanded' => true,
//                'multiple' => true,
//                'label' => false,
//                'label_attr' => [
//                    'class' => 'list-group-item list-group-item-action'
//                ],
//                'query_builder' => function (EntityRepository $entityRepository) {
//                    return $entityRepository->createQueryBuilder('user')
//                        ->orderBy('user.lastName', 'ASC');
//                }
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'status' => 0,
        ]);
    }
}
