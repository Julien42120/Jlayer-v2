<?php

namespace App\Form;

use App\Entity\CategoryFichier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => CategoryFichier::class,
                "attr" => [
                    'label' => "Categorie"
                ]
            ])
            ->add('category', TextType::class, [
                "attr" => [
                    'class' => "InputCat",
                    'placeholder' => "Ajout d'une catégories",

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryFichier::class,
        ]);
    }
}
