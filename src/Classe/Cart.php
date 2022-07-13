<?php

namespace App\Classe;


    use Symfony\Component\HttpFoundation\RequestStack;


class Cart{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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

        // gets an attribute by name
        //$foo = $session->get('foo');

        // the second argument is the value returned when the attribute doesn't exist
       // $filters = $session->get('filters', []);

        // ...
    
}

