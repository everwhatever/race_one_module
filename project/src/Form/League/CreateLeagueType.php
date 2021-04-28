<?php

namespace App\Form\League;


use App\Entity\Driver;
use App\Entity\League;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateLeagueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'attr' => ['placeholder' => 'nazwa wyścigu',
                'label' => 'wpisz nazwę',
                'required' => true]
        ])
            ->add('drivers', EntityType::class, [
                'class' => Driver::class,
                'choice_label' => function (Driver $driver) {
                    return sprintf("(%d) %s", $driver->getId(), $driver->getEmail());
                },
                'placeholder' => 'choose a driver',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add("races", CollectionType::class, [
                'entry_type' => CreateRaceForLeagueType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
            ])
            ->add('send', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => League::class
        ]);
    }
}