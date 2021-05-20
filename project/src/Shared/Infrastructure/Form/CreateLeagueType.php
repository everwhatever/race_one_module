<?php

namespace App\Shared\Infrastructure\Form;


use App\Driver\Infrastructure\Repository\DriverRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateLeagueType extends AbstractType
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'attr' => ['placeholder' => 'nazwa wyścigu',
                'label' => 'wpisz nazwę',
                'required' => true]
        ])
            ->add('driversIds', ChoiceType::class, [
                'choices' => $this->getDriversIds(),
                'placeholder' => 'wybierz kierowcę',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add("racesNames", CollectionType::class, [
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

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => League::class
//        ]);
//    }

    private function getDriversIds(): array
    {
        $drivers = $this->driverRepository->findAll();
        $driversIds = [];
        foreach ($drivers as $driver){
            $driversIds[$driver->getEmail()] = $driver->getId();
        }
        return $driversIds;
    }
}