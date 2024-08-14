<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password_input', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe actuel',
                'invalid_message' => 'le mot de passe actuel est incorrect',
                'attr' => [
                    'placeholder' => 'veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password_input', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'label' => 'nouveau mot de passe',
                'invalid_message'=>'le nouveau mot de passe et la confirmation doivent être identique.',
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisir votre nouveau mot de passe'
                    ],
                    'hash_property_path' => 'password',
                    ],
                'second_options' => [
                    'label' => 'Confirmation nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisir la confirmation du mot de passe'
                    ]
                    ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon mot de passe',
                'attr' => [
                        'class' => 'btn btn-success'
                    ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                // Importer le form :
                $form = $event->getForm();
                // Récuperer le mot de passe actuel saisi dans le form :
                $old_password_input = $form->get('old_password_input')->getData();
                // Récuperer l'user actuel (passé dans le form) : 
                $user = $form->getConfig()->getOptions()['data'];
                // Récuperer UserPasswordHasherInterface (passé dans le form) : 
                $hasher = $form->getConfig()->getOptions()['hasher'];
                // Comparer le mot de passe saisi et le mot de passe en base de données :
                $isValid = $hasher->isPasswordValid(
                    $user,
                    $old_password_input
                );
                // Si is valid est false, creer un message d'erreur :
                if(!$isValid){
                    $form->get('old_password_input')->addError(new FormError('Votre mot de passe est incorect.'));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'hasher' => false
        ]);
    }
}
