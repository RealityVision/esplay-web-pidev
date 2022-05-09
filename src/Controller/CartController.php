<?php

namespace App\Controller;

use App\Repository\Produit2Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(SessionInterface $session, Produit2Repository $produit2Repository)
    {
        $panier = $session->get('panier', []);

        $panierWithData = [] ;
        foreach ($panier as $idp2 => $quantity) {
            $panierWithData[] = [
                'produit2' => $produit2Repository->find($idp2),
                'quantity' => $quantity
            ];
        }
            dd($panierWithData);
        return $this->render('cart/index.html.twig', []);
    }


    /**
     * @Route("/panier/add/{idp2}", name="cart_add")
     */

    public function add($idp2, SessionInterface $session)
    {
       
        $panier = $session->get('panier', []);


        if (!empty($panier[$idp2])) {
            $panier[$idp2]++;
        } else {
            $panier[$idp2] = 1;
        }


            $session->set('panier', $panier);
            dd($session->get('panier'));

    }
}