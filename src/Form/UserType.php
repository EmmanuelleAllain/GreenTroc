<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TypeTextType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('firstname',TypeTextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TypeTextType::class, [
                'label' => 'Nom de famille'
            ])
            ->add('address', TypeTextType::class, [
                'label' => 'Adresse postale'
            ])
            ->add('postcode', NumberType::class, [
                'label' => 'Code postal'
            ])
            ->add('city', TypeTextType::class, [
                'label' => 'Ville'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
