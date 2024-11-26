<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AdressUserType extends AbstractType
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
            ->add('adress', TextType::class, [
                'label' => 'Rue',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 200
                ]),
                'attr' => [
                    'placeholder' => 'Saisir le numéro et le nom de votre rue'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisir votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisir le nom de votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 200
                ]),
                'attr' => [
                    'placeholder' => 'Saisir votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
