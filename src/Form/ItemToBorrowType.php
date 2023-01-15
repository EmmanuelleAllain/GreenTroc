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
            //todo : secure the form so users cannot set category to null
            ->add('categoryType', null, ['label' => 'Catégorie', 'choice_label' => function ($categoryType) {
                return $categoryType->getCategoryName();
            },
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
