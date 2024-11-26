<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    /*
    * 1 ere étape du tunnel d'achat
    * choix de l'adresse de livraison et du transporteur
    */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $adresses = $this->getUser()->getAdresses();

        if (count($adresses) == 0) {
            return $this->redirectToRoute('app_account_adress_form');
        }
        // Créer le form grace a la class RegisterUserType :
        // deuxieme parametre a null car il concerne le mapping avec une entité précise
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $adresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    /*
    * 2eme étape du tunnel d'achat
    * Récap de la commande de l'utilisateur
    * Insertion en base de données
    * Préparation de paiement vers stripe
    */
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        $products = $cart->getCart();

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }
        // Créer le form grace a la class RegisterUserType :
        // deuxieme parametre a null car il concerne le mapping avec une entité précise
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAdresses(),
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Stocker les information de commande en BDD :
            // Creation de la chaine adresse :
            $addressObj = $form->get('addresses')->getData();
            $addressObj = $form->get('addresses')->getData();
            $address = $addressObj->getFirstname() . ' ' . $addressObj->getLastname() . '<br/>';
            // .= signifie prendre la variable et ajoute a la suite :
            $address .= $addressObj->getAdress() . '<br/>';
            $address .= $addressObj->getPostal() . ' ' . $addressObj->getCity() . '<br/>';
            $address .= $addressObj->getCountry() . '<br/>';
            $address .= $addressObj->getPhone();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime());
            $order->setState(state: 1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);

            // Parcourire le panier pour avoir le detail de la commande :
            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['product']->getName());
                $orderDetail->setProductIllustration($product['product']->getIllustration());
                $orderDetail->setProductQuantity($product['qty']);
                $orderDetail->setProductPrice($product['product']->getPrice());
                $orderDetail->setProductTva($product['product']->getTva());
                // Ne pas oublier de modifier Order avec cascade: ['persist'] pour lui permettre de manipuler les données de OrderDetail
                $order->addOrderDetail($orderDetail);
            }

            // Fige la data et la prepare a etre creer :
            $entityManager->persist($order);
            // Prend l'objet que tu a figer et enregistre le en bdd
            $entityManager->flush();
        }

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'totalPriceWt' => $cart->totalPriceWt(),
        ]);
    }
}
