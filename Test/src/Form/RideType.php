<?php

namespace App\Form;

use App\Entity\Ride;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Departure', TextType:: class, [
                'empty_data' => ''])
            ->add('Arrival')
            ->add('DepartureDate', null, [
                'widget' => 'single_text',
            ])
            ->add('ArrivalDate', null, [
                'widget' => 'single_text',
            ])
            ->add('Driver')
            ->add('Car')
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('Departure','-','Arrival'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
