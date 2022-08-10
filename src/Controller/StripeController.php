<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]

    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';

        
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if(!$order){
            
            $this->redirectToRoute('order');
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object =  $entityManager->getRepository(Product::class)->findOneBy(['name' => $product->getProduct()]);;
            $product_for_stripe[] = [
                'price_data' => [
                    'currency'=> 'eur',
                    'unit_amount'=>$product->getPrice(),
                    'product_data'=>[
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/". $product_object->getIllustration()]
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }


        $product_for_stripe[] = [
            'price_data' => [
                'currency'=> 'eur',
                'unit_amount'=>$order->getCarrierPrice() * 100,
                'product_data'=>[
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN]
                ],
            ],
            'quantity' => 1,
        ];


        Stripe::setApikey('sk_test_51LTAcVKHduKlGcIHJwiEvQHGnk9fTGFHyQHrA8Zf5W6YgxY5oMRxVcF9khcFQM56ASPqTQYhgsgModzqLnRO9R8Q004imAGzFV');
        

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
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
