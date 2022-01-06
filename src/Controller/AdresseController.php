<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/adresse")
 */
class AdresseController extends AbstractController
{

    public function __construct(PanierService $panierService )
    {
        
        $this->panierService = $panierService;

        
        
    }
    /**
     * @Route("/", name="adresse_index", methods={"GET"})
     */
    public function adresse(AdresseRepository $adresseRepository): Response
    {

        $totalPanier =$this->panierService->getTotalPanier();

        return $this->render('adresse/adresse.html.twig', [
            'adresses' => $adresseRepository->findAll(),
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/new", name="adresse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        $totalPanier =$this->panierService->getTotalPanier();

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$this->getUser();
            $adresse->setUser($user);
            $entityManager->persist($adresse);
            $entityManager->flush();

            $this->addFlash("adresse_message", "Votre adresse a bien été enregistrée");
            
            return $this->redirectToRoute('compte');
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/{id}", name="adresse_show", methods={"GET"})
     */
    public function show(Adresse $adresse): Response
    {

        $totalPanier =$this->panierService->getTotalPanier();
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adresse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        $totalPanier =$this->panierService->getTotalPanier();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("adresse_message", "Votre adresse a bien été modifiée");

            return $this->redirectToRoute('compte');
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/{id}", name="adresse_delete", methods={"POST"})
     */
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();

            $this->addFlash("adresse_message", "Votre adresse a bien été suprimée");
        }

        return $this->redirectToRoute('compte');
    }
}
