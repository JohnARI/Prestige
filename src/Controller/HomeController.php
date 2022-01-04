<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();


        return $this->render('home/home.html.twig',[
            'products'=> $products
        ]);
    }

    /**
     * @Route("/montreshommes", name="montres_hommes")
     */
    public function montresHommes(?Category $category): Response {
    
        if($category){
            $products = $category->getProducts()->getValues(); //Le getArticles pour récupérer les articles et getValues les valeures des produits

        }else{

            $products = null;
            return $this->redirectToRoute('home');

        }

        $categories = $this->repoCategory->findAll();

        return $this->render("montres/montres_hommes.html.twig",[
            'products' => $products,
            'categories'=>$categories
        ]);
    }   
    
    /**
     * @Route("/montresfemmes", name="montres_femmes")
     */
    public function montresFemmes(): Response {
    
        $products = $this->entityManager->getRepository(Product::class)->findAll();
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
