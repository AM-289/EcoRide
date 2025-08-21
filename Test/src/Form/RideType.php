<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Ride;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideType extends AbstractType
{

    public function __construct(private FormListenerFactory $listenerFactory){
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure', TextType:: class, [
                'empty_data' => ''])
            ->add('arrival')
            ->add('departureDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('arrivalDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'expanded' => true,
                'choice_label' => 'brand'
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('departure'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
