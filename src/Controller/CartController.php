<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Produit2Repository;
use App\Service\CartService;
use App\Entity\Produit2;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;



class CartController extends AbstractController
{
    /**
     * @Route("/front/cart", name="cart_index")
     */
    public function index(CartService $cartService)
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/cart/add/{idp2}", name="cart_add")
     */

    public function add($idp2, CartService $cartService){
      $cartService->add($idp2);
        return $this->redirectToRoute('cart_index');
    }
    /**
     * @Route("/cart/remove/{idp2}", name="cart_remove")
     */
    public function remove($idp2, CartService $cartService)
    {
          $cartService->remove($idp2);

        return $this->redirectToRoute('cart_index');
    }
    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove('cart');

        return $this->redirectToRoute('cart_index');
    }
    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout() {
        $em= $this->getDoctrine()->getManager();
        $Produit2=$em->getRepository(Produit2::class);

        \Stripe\Stripe::setApiKey('your stripe key');


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => '$Produit',
                    ],
                    'unit_amount' => '200',
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
          //  'success_url' => $this->generateUrl('succes', [], UrlGeneratorInterface::ABSOLUTE_URL),
            //'cancel_url' => $this->generateUrl('erreur', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new JsonResponse([ 'id' => $session->id ]);
    }

    /**
     * @Route("/success", name="success")
     */
    public function success(CartService $cartService, \Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message('New'))

            ->setFrom('slim.derouich.8b7@gmail.com')

            ->setTo('slim.derouich.8b7@gmail.com')

            ->setSubject('enregistrée !')


            ->setBody(
                $this->renderView(
                    'cart/Commande.html.twig'),

                'text/html'
            );


        $mailer->send($message);
        $this->addFlash('message', 'le message a bien ete envoye');
        return $this->render('produit2/Success.html.twig');
    }
    /**
     * @Route("/error", name="error")
     */
    public function error()
    {
        return $this->render('produit/Error.html.twig');
    }


}
