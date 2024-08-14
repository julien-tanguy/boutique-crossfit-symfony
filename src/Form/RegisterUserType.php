<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisir votre Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisir votre Nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisir votre Email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'label' => 'Mot de passe',
                'required'=>true,
                'invalid_message'=>'le mot de passe et la confirmation doivent être identique.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisir votre mot de passe'
                    ],
                    'hash_property_path' => 'password',
                    ],
                'second_options' => [
                    'label' => 'Confirmation mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisir la confirmation du mot de passe'
                    ]
                    ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                        'class' => 'btn btn-success'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity(
                    [
                        'entityClass' => User::class,
                        'fields' => 'email'
                    ]
                )

            ],
            'data_class' => User::class,
        ]);
    }
}
