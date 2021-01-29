<?php


namespace App\Form;


use App\Entity\Driver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class GetEmailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EntityType::class,[
                'class'=>Driver::class,
                'choice_label'=>function(Driver $driver){
                    return sprintf("(%d) %s",$driver->getId(),$driver->getEmail());
                },
                'placeholder'=>'choose a driver',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add('send', SubmitType::class);
    }
}