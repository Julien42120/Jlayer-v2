<?php

namespace App\Form;

use App\Entity\CategoryFichier;
use App\Entity\Fichiers;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichiersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => CategoryFichier::class,
                "attr" => [
                    'label' => "Category"
                ]
            ])
            ->add('nom', TextType::class, [
                "attr" => [
                    'class' => "NomFichier",
                    'placeholder' => "Nom du fichier"
                ]
            ])
            ->add('description', TextareaType::class, [
                "attr" => [
                    'class' => "textArea",
                    'placeholder' => "Description de votre fichier STL"
                ]
            ])
            ->add('images', FileType::class, [
                'mapped' => false,
                'required' => false,
                "attr" => [
                    'class' => "form-control btn btn-outline-light",
                ],
            ])
            ->add('fichierSTL', FileType::class,  [
                "attr" => [
                    'class' => "form-control btn btn-outline-light ",
                ],
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichiers::class,
        ]);
    }
}
