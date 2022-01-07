<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService{

    private $session;
    private $repoProduct;
    private $tva = 0.2;

    public function __construct(SessionInterface $session, ProductRepository $repoProduct){

        $this->session = $session;
        $this->repoProduct =$repoProduct;

    }

    public function addToCart($id){

        $cart=$this->getCart(); //récupérer le panier

          if (empty($panier[$id])) :
            $cart[$id] = 1;
        else :
            $cart[$id]++;
        endif;

        $this->updateCart($cart); //MAJ de la session
    }

    public function deleteFromCart($id){
        $cart=$this->getCart();
        if (isset($cart[$id])){
            //produits déjà dans le panier
            if($cart[$id] > 1){
                //produit existe + d'une fois
                $cart[$id]--; // quand il a plus d'un produit dans le panier, on décrémente
            }else{
                // produit existe une fois
                unset($cart[$id]); // quand le produit existe une fois, on le retire
            }
        }
        $this->updateCart($cart); //MAJ de la session
    }

    public function deleteAllToCart($id){
        $cart=$this->getCart();
        if (isset($cart[$id])){
            //produits déjà dans le panier
                unset($cart[$id]); // quand le produit existe, on le retire
                $this->updateCart($cart); //MAJ de la session
            }
        
    }

    public function deleteCart(){
        $this->updateCart([]); // mettre le panier à 0

    }

    public function updateCart($cart){

        $this->session->set('cart', $cart);
        $this->session->set('cartData', $this->getFullCart());
    }

    public function getCart(){
        return $this->session->get('cart',[]);

    }
  
    public function getFullCart(){
        $cart=$this->getCart(); //récupérer le panier

        $fullCart = [];// les produits récupérés seront ajoutés à ce tableau
        $quantity_cart = 0; // quantité du panier
        $subTotal = 0; //montant total du panier
        foreach ($cart as $id =>$quantity){
            //faire une boucle pour récupérer les produits du panier 
            $product = $this->repoProduct->find($id);

            if($product){
                //produit récupéré avec succès
                $fullCart['products'][]=
                [
                    "quantity" => $quantity,
                    "product" => $product
                ];
                $quantity_cart += $quantity; // incrémenter la quantité du panier avec la quantité du produit
                $subTotal += $quantity * $product->getPrice()/100; // incrémenenter le montant  avec la quantité de produit multiplié par le prix réel unitaire du produit
            }else{
                //id incorrect
                $this->deleteFromCart($id);// permet la suppression et la MAJ du panier            }

            }
    
        } 
        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
            "Taxe" => round($subTotal * $this->tva,2),
            "subTotalTTC" => round(($subTotal + ($subTotal * 0.2))),
        ];

        return $fullCart;
    }
    
}