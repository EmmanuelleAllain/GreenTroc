<?php

namespace App\Form;

use App\Entity\ItemToBorrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ItemToBorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TypeTextType::class, [
                'label' => 'Titre de l\'objet'
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Aucune' => null,
                    'Bricolage' => 'Bricolage',
                    'Puériculture' => 'Puériculture',
                    'Cuisine' => 'Cuisine',
                    'Divers' => 'Divers'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('pictureFile', VichFileType::class, [
                'label' => 'Photo de l\'objet',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemToBorrow::class,
        ]);
    }
}
