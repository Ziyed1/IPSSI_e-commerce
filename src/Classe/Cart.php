<?php

namespace App\Classe;


    use Symfony\Component\HttpFoundation\RequestStack;
    use App\Entity\Product;
    use Doctrine\ORM\EntityManagerInterface;
    


class Cart{
    private $requestStack;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

       //pour proceder aux ajouts 
        public function add($id){
            $session = $this->requestStack->getSession();
            $cart = $session->get('cart',[]);
            if(!empty($cart[$id])){
                $cart[$id] ++;
            }else{
                $cart[$id] = 1;
            }

            // stores an attribute in the session for later reuse
            $session->set('cart', $cart);
        }

        public function get(){
            $session = $this->requestStack->getSession();

            return $session->get('cart');

        }


        public function remove(){
            $session = $this->requestStack->getSession();

            return $session->remove('cart');

        }


        public function delete($id){
            $session = $this->requestStack->getSession();

            $cart = $session->get('cart',[]);
            unset($cart[$id]);

            return $session->set('cart', $cart);

        }


        public function decrease($id){
            $session = $this->requestStack->getSession();
            $cart = $session->get('cart',[]);

            if($cart[$id] > 1){
                $cart[$id] --;
            }else{
                unset($cart[$id]) ;
            }
            $session->set('cart', $cart);
        }


       //optimiser la recuperation des données  
       public function getFull(){

        $cartComplete = [];
        
        //pour eviter l'erreur dans le cas ou le panier est vide 
        if($this->get()){
            //pour recuperer toutes les infos liée au produit
        foreach($this->get() as $id => $quantity){
            $product_object = $this->entityManager->getRepository(Product::class)->findOneByid($id);

            //en cas d'ajout d'un produit qui n'existe pas via l'url (securité)
            if(!$product_object){
                $this->delete($id);
                continue;
            }
            $cartComplete [] = [
                'product' =>  $product_object,
                'quantity' => $quantity
            ];
        }
        return $cartComplete;

        }

       } 

    
}

