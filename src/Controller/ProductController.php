<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/nos-produits', name: 'app_products')]
    public function index(): Response
    {
       //Requête SQL 'findAll()' pour récupérer tous les produits avec l'ORM doctrine
        $products = $this->entityManager->getRepository(Product::class)->findAll();


        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    //Ajout d'accollade dans route pour la prise en compte de la valeur dynamique de la route
    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug): Response
    {

        //Requête SQL 'findOneBySlug()' permettant de recupérer un produit en fonction du slug
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
