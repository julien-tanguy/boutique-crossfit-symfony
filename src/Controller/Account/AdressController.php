<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'app_account_adresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    #[Route('/compte/adresses/delete/{id}', name: 'app_account_adress_delete')]
    public function delete($id, AdressRepository $adressRepository): Response
    {
        $adress = $adressRepository->findOneById($id);
        if (!$adress or $adress->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_adresses');
        }
        // Creation message de validation : 
        $this->addFlash('danger', 'Votre adresse a été supprimée.');
        //suppression de l'adresse
        $this->entityManager->remove($adress);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_account_adresses');
    }

    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_adress_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AdressRepository $adressRepository, Cart $cart): Response
    {
        if ($id) {
            $adress = $adressRepository->findOneById($id);
            if (!$adress or $adress->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_adresses');
            }
        } else {
            $adress = new Adress();
            $adress->setUser($this->getUser());
        }

        $form = $this->createForm(AdressUserType::class, $adress);
        // Lui demander d'ecouter la requete :
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Fige la data et la prepare a etre creer :
            $this->entityManager->persist($adress);
            // Prend l'objet que tu a figer et enregistre le en bdd
            $this->entityManager->flush();
            // Creation message de validation : 
            $this->addFlash('success', 'Votre adresse a été enregistrée.');
            // Rediriger :
            // Si produit dans le panier :
            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute('app_order');
            }
            // Sinon :
            return $this->redirectToRoute('app_account_adresses');
        }
        return $this->render('account/address/form.html.twig', [
            'adressForm' => $form->createView()
        ]);
    }
}
