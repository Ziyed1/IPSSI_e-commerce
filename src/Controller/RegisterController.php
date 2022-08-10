<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Création d'un utilisateur
        $user = new User();

        // Création d'un formulaire
        $form = $this->createForm(RegisterType::class, $user);

        // Ecoute de la requete
        $form->handleRequest($request);

        // Controle de la soumission et de la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()){

            // Ajout des données du formulaire dans user
            $user = $form->getData();

            // Encodage du mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            // Insertion de la data dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->render('home/index.html.twig');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
