<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, ProductRepository $repoProduit )
    {
        $this->entityManager = $entityManager;
        $this->repoProduit = $repoProduit;
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $produitBestSeller = $this->repoProduit->findBybestseller(1);


        return $this->render('home/home.html.twig',[

            'products'=> $products,
            'productBestSeller'=>$produitBestSeller
        ]);
    }

   /**
     * @Route("/montreshommes", name="montres_hommes")
     */
    public function montresHommes(

    ): Response {
    
       

        $products = $this->entityManager->getRepository(Product::class)->findByCategory(1);

        

        return $this->render("montres/montres_hommes.html.twig",[
            'products' => $products,
            
        ]);
    }   
    
    /**
     * @Route("/montresfemmes", name="montres_femmes")
     */
    public function montresFemmes(): Response {
    
        $products = $this->entityManager->getRepository(Product::class)->findByCategory(2);

        
        return $this->render("montres/montres_femmes.html.twig",[
            'products' => $products
        ]);
    } 

    /**
     * @Route("/toutes_les_montres", name="toutes_les_montres")
     */
    public function toutesMontres(): Response {
    
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return $this->render("montres/toutes_les_montres.html.twig",[
            'products' => $products
        ]);
    } 
        
    
}
