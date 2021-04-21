<?php


namespace App\Form;


use App\Entity\Driver;
use App\Entity\Race;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateRaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'nazwa wyścigu',
                    'label' => 'wpisz nazwę',
                    'required' => true]
            ])
            ->add('send', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Race::class
        ]);
    }
}