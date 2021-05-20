<?php


namespace App\Shared\Infrastructure\Form;


use App\Driver\Domain\Model\Driver;
use App\Driver\Infrastructure\Repository\DriverRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateRaceType extends AbstractType
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('drivers', EntityType::class, [
//                'class' => Driver::class,
//                'choice_label' => function (Driver $driver) {
//                    return sprintf("(%d) %s", $driver->getId(), $driver->getEmail());
//                },
//                'placeholder' => 'choose a driver',
//                'expanded' => true,
//                'multiple' => true,
//                'required' => true,
//            ])
            ->add('driversIds', ChoiceType::class, [
                'choices' => $this->getDriversIds(),
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