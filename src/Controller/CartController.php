<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\Panier\PanierService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $cartService;

    public function __construct(CartService $cartService, PanierService $panierService)
    {
        $this->cartService = $cartService;
        $this->panierService = $panierService;
    }
    /**
     * @Route("/cart", name="cart")
     */
    public function index(): Response
    { 
        $cart = $this->cartService->getFullCart();
        $totalPanier =$this->panierService->getTotalPanier();

    //    dd($cart);

        $this->cartService->addToCart(3); // ajouter un élément à notre panier

        return $this->render('panier/panier.html.twig', [
            'cart' => $cart,
            'TotalPanier'=>$totalPanier


        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function addToCart($id) : Response
    { 
        $this->cartService->addToCart($id); // ajouter un élément à notre panier
            return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function deleteFromCart($id) : Response
    {
        $this->cartService->deleteFromCart($id); // supprimer un élément à notre panier
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete-all/{id}", name="cart_delete_all")
     */
    public function deleteAllToCart($id) : Response
    {
        $this->cartService->deleteAllToCart($id); // supprimer le panier
        return $this->redirectToRoute('cart');
    }
}

