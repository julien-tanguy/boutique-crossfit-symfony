<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Permettre de ne pas avoir a recharger les images d'un produit lors de la modification :
        $required = true;
        if($pageName == 'edit'){
            $required = false;
        }

        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Nom de votre produit'),
            SlugField::new('slug')->setLabel('Url')->setTargetFieldName('name')->setHelp('Url de votre catégorie générée automatiquement'),
            TextEditorField::new('description')->setLabel('Déscription')->setHelp('Déscription de votre produit'),
            ImageField::new('illustration')
                ->setLabel('Image')
                ->setHelp('Image de votre produit en 600x600px')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setUploadDir('public/uploads')
                ->setBasePath('/uploads')
                ->setRequired($required),
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Prix hors taxes de votre produit'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setHelp('Taux de TVA de votre produit')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '19,6%' => '19.6'
            ]),
            AssociationField::new('category')->setLabel('Catégorie')->setHelp('Catégorie de votre produit')
        ];
    }
}
