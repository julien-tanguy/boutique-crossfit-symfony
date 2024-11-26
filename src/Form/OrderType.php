<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraions',
                'required' => true,
                'class' => Adress::class,
                'choices' => $options['addresses'],
                //case a cocher :
                'expanded' => true,
                //lire les balise html des fonction (utile pour la fonction __toString de l'entité Adress) :
                'label_html' => true
            ])
            ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class' => Carrier::class,
                //case a cocher :
                'expanded' => true,
                //lire les balise html des fonction (utile pour la fonction __toString de l'entité Adress) :
                'label_html' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'w-100 btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}
