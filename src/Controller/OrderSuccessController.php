<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Classe\Cart;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]

    public function index(Cart $cart, $stripeSessionId): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        

        if(!$order->isIsPaid()){
            //vider le panier en cas de succes
            $cart->remove();
            //modifier l'etat de paiement de la commande <ispaid> et afficher info de l'user
            $order->setIspaid(1);
            $this->entityManager->flush();

        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
