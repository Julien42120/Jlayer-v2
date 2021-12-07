<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', null, [
                "attr" => [
                    'class' => "input",
                    'placeholder' => "Modification Pseudo",
                ]
            ])
            // ->add('password', null, [
            //     "attr" => [
            //         'class' => "input",
            //         'placeholder' => "Modification mot de passe",
            //     ]
            // ])
            ->add('avatar', FileType::class, [
                "attr" => [
                    'class' => "form-control btn btn-outline-light ",
                ],
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
