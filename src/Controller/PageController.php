<?php

namespace App\Controller;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/page/{id}', name: 'app_page')]
    public function index($id): Response
    {

        //Requête en BDD réqupérant les données d'une page spécifique en fonction de l'id
        $page = $this->entityManager->getRepository(Page::class)->find($id);

        if(!$id) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('page/index.html.twig',[
            'page' => $page
        ]);
    }
}
