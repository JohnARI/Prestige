<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $repoProduit, PanierService $panierService)
    {
        $this->entityManager = $entityManager;
        $this->repoProduit = $repoProduit;
        $this->panierService = $panierService;
    }

    

    /**
     * @Route("/compte", name="compte")
     */
    public function monCompte(): Response
    {

        $totalPanier =$this->panierService->getTotalPanier();


        return $this->render('compte/compte.html.twig', [

            'controller_name' => 'CompteController',
            'TotalPanier'=>$totalPanier
        ]);
    }
}
