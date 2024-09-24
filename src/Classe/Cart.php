<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    /*
    * add()
    * fonction permettant d'ajouter un produit au panier
    */
    public function add($product)
    {
        // Appeler la sessions de symfony
        //$session = $this->requestStack->getSession();
        $cart = $this->requestStack->getSession()->get('cart');
        // Ajouter une quantité + 1 
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'product' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'product' => $product,
                'qty' => 1
            ];
        }

        // Creer la sessions cart, set à 2 paramétres => le nom de la sessions et une valeur(ici le tableau $cart avec l'identifiant et la quantité)
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
    * decrease()
    * fonction permettant de retirer -1 quantité ou un produit(si quantité = 1) au panier
    */
    public function decrease($id)
    {
        // Appeler la sessions de symfony
        //$session = $this->requestStack->getSession();
        $cart = $this->requestStack->getSession()->get('cart');
        // Ajouter une quantité + 1 
        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            // Unset() fonction php pour supprimer une entrée d'un tableau
            unset($cart[$id]);
        }

        // Creer la sessions cart, set à 2 paramétres => le nom de la sessions et une valeur(ici le tableau $cart avec l'identifiant et la quantité)
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
    * fullQuantity()
    * fonction permettant de retourner le nombre total de produit au panier
    */
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;
        if (!isset($cart)) {
            return $quantity;
        }
        foreach ($cart as $product) {
            $quantity = $quantity + $product['qty'];
        }
        return $quantity;
    }

    /*
    * totalPriceWt()
    * fonction retournant le prix total ttc du panier
    */
    public function totalPriceWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $totalPrice = 0;
        if (!isset($cart)) {
            return $totalPrice;
        }
        foreach ($cart as $product) {
            $totalPrice = $totalPrice + ($product['product']->getPriceWt() * $product['qty']);
        }
        return $totalPrice;
    }

    /*
    * getCart()
    * fonction retournant le panier
    */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    /*
    * remove()
    * fonction supprimant le panier
    */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
}
