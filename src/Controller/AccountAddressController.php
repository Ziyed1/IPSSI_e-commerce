<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
/* Générer l'interface de l'ORM doctrine pour l'ajout en BDD */

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'app_account_address')]
    public function index(): Response
    {


        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-une-adresse', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {

        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Lier une adresse à un utilisateur
            $address->setUser($this->getUser());

            /*Fige et envoie la donnée address*/
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            /* Controle si l'utilisateur courant à des produits dans le panier */
            if($cart->get()) {
                return $this->redirectToRoute('app_order');

            } else {
                /* Redirection après l'envoie du formulaire */
                return $this->redirectToRoute('app_account_address');
            }

        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/compte/modifier-une-adresse/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id)
    {

        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if(!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');

        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            /* Redirection après l'envoie du formulaire */
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'app_account_address_delete')]
    public function delete($id)
    {

        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }


            return $this->redirectToRoute('app_account_address');
    }
}
