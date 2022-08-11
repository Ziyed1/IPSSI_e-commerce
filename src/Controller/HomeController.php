<?php

namespace App\Controller;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;

class HomeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_home')]
    public function index(SessionInterface $session): Response
    {


        $pages = $this->entityManager->getRepository(Page::class)->findAll();


        $cart = $session->get('cart');
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        return $this->render('home/index.html.twig', [
            'pages' => $pages,
            'products' => $products
        ]);

    }
}
