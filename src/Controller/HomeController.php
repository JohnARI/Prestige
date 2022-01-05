<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Service\Panier\PanierService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, ProductRepository $repoProduit, PanierService $panierService )
    {
        $this->entityManager = $entityManager;
        $this->repoProduit = $repoProduit;
        $this->panierService = $panierService;
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        

        $totalPanier =$this->panierService->getTotalPanier();

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $produitBestSeller = $this->repoProduit->findBybestseller(1);


        return $this->render('home/home.html.twig',[

            'products'=> $products,
            'productBestSeller'=>$produitBestSeller,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route(" /marque", name="marque")
     */

    public function marque(): Response {
    
        return $this->render("/home/marque.html.twig",[
            
        ]);
    } 

   /**
     * @Route("/montreshommes", name="montres_hommes")
     */
    public function montresHommes(): Response {
    
       

        $totalPanier = $this->panierService->getTotalPanier();

        $products = $this->entityManager->getRepository(Product::class)->findByCategory(1);

        

        return $this->render("montres/montres_hommes.html.twig",[
            'products' => $products,
            'TotalPanier'=>$totalPanier
            
        ]);
    }   
    
    /**
     * @Route("/montresfemmes", name="montres_femmes")
     */
    public function montresFemmes(): Response {

        $totalPanier = $this->panierService->getTotalPanier();
    
        $products = $this->entityManager->getRepository(Product::class)->findByCategory(2);

        
        return $this->render("montres/montres_femmes.html.twig",[
            'products' => $products,
            'TotalPanier'=>$totalPanier
        ]);
    } 

    /**
     * @Route("/toutes_les_montres", name="toutes_les_montres")
     */
    public function toutesMontres(): Response {

        $totalPanier = $this->panierService->getTotalPanier();
    
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return $this->render("montres/toutes_les_montres.html.twig",[
            'products' => $products,
            'TotalPanier'=>$totalPanier
        ]);
    } 
        

    /**
     * @Route("/addCart/{id}/{route}/$_GET", name="addCart")
     *
     */
    public function addCart($id, PanierService $panierService, $route)
    {

        
        $panierService->add($id);

        ($panierService->getFullCart());

        $route = $_GET;

        if ($route == 'montreshommes'):
            $this->addFlash('success', 'montre ajouté au panier');
            return $this->redirectToRoute('montres_hommes');
        else:
            $this->addFlash('success', 'montre ajouté au panier');
            return $this->redirectToRoute('fullCart');
        endif;

    }
    
    /**
     * @Route("/removeCart/{id}", name="removeCart")
     *
     */
    public function removeCart($id, PanierService $panierService)
    {
        $panierService->remove($id);
        return $this->redirectToRoute('fullCart');


    }

    /**
     * @Route("/deleteCart/{id}", name="deleteCart")
     *
     */
    public function deleteCart($id, PanierService $panierService)
    {
        $panierService->delete($id);
        return $this->redirectToRoute('fullCart');


    }

    /**
     * @Route("/fullCart", name="fullCart")
     * @Route("/order/{param}", name="order")
     *
     */
    public function fullCart(PanierService $panierService,  $param = null)
    {



        $fullCart = $panierService->getFullCart();

        $totalPanier ="";

        $total=$panierService->getTotal();

        return $this->render('home/fullCart.html.twig', [
            'fullCart' => $fullCart,
            'total'=>$total,
            'TotalPanier'=>$totalPanier


        ]);

    }

    /**
     * @Route("/delivery", name="delivery")
     */
    public function delivery()
    {
        
        return $this->render('home/delivery.html.twig');
    }
    


    /**
     *
     * @Route("/finalOrder", name="finalOrder")
     *
     */
    public function order( PanierService $panierService, EntityManagerInterface $manager)
    {



            $order = new Order();
            $order->setDate(new \DateTime())->setUser($this->getUser());
            $panier = $panierService->getFullCart();
            
//            $delivery=new Delivery();
//            
//            $delivery->setOrder($order)->setStreet($request->request->get('street'));
//                
//                $manager->persist($delivery);

            foreach ($panier as $item):

                $cart = new Cart();
                $cart->setOrders($order)->setProduct($item['product'])->setQuantity($item['quantity']);
                $manager->persist($cart);
                $panierService->delete($item['product']->getId());
            endforeach;
            $manager->persist($order);
            $manager->flush();
            $this->addFlash('success', "Merci pour votre achat");
            return $this->redirectToRoute('home');




    }

}


    

