<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'app_stripe_create_session')]

    public function index(Cart $cart): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';


        foreach ($cart->getFull() as $product) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency'=> 'eur',
                    'unit_amount'=>$product['product']->getPrice(),
                    'product_data'=>[
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()]
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }

        Stripe::setApikey('sk_test_51LTAcVKHduKlGcIHJwiEvQHGnk9fTGFHyQHrA8Zf5W6YgxY5oMRxVcF9khcFQM56ASPqTQYhgsgModzqLnRO9R8Q004imAGzFV');
        

        $checkout_session = Session::create([
            'line_items' => [
            $product_for_stripe
            ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
    ]);


    //$response = new JsonResponse(['id'=> $checkout_session->id]);
    return $this->redirect($checkout_session->url);

        
    }
}
