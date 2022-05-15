<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\Produit2Repository;

class CartService
{

    protected $session;
    protected $produit2Repository;

    public function __construct(SessionInterface $session, Produit2Repository $produit2Repository)
    {
        $this->session = $session;
        $this->produit2Repository = $produit2Repository;
    }

    public function add(int $idp2)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$idp2])) {
            $cart[$idp2]++;
        } else {
            $cart[$idp2] = 1;
        }


        $this->session->set('cart', $cart);
    }

    public function remove(int $idp2)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$idp2])) {
            unset($cart[$idp2]);
        }
        $this->session->set('cart', $cart);
    }

    public function getFullcart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData  = [];
        foreach ($cart as $idp2 => $quantity) {
            $cartWithData[] = [
                'produit' => $this->produit2Repository->find($idp2),
                'quantity' => $quantity
            ];
        }
        return $cartWithData;
    }


    public function getTotal(): float
    {
        $total = 0;


        foreach ($this->getFullcart() as $cart) {

            $total += $cart['produit']->getPrix() * $cart['quantity'];
        }
        return $total;
    }
}
