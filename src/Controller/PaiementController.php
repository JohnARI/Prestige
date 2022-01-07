<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;
use App\Form\PaiementType;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/paiement")
 */
class PaiementController extends AbstractController
{
    private $PanierServices;
    private $session;

    public function __construct(PanierService $PanierServices, SessionInterface $session, PanierService $panierService, EntityManagerInterface $entityManager)
    {
        $this->PanierServices = $PanierServices;
        $this->session = $session;
        $this->panierService = $panierService;
        $this->entityManager = $entityManager;
    }



    /**
     * @Route("/", name="paiement")
     */
    public function index(Request $request): Response
    {

        $adresse = new Adresse;
        $panier = $this->PanierServices->getFullCart();
        $totalPanier = $this->panierService->getTotalPanier();

        //dd($panier);

        if (!empty($panier['product'])) {
            return $this->redirectToRoute("home");
        }

        $user = $this->getUser();
        if (!$adresse->setUser($user)) {
            $this->addFlash('paiement_message', 'Veuillez ajouter une adresse pour continuer !');
            return $this->redirectToRoute("adresse_new");
        }

        if ($this->session->get('paiement_data')) {
            return $this->redirectToRoute("paiement_confirm");
        }

        $form = $this->createForm(PaiementType::class, null, ['user' => $user]);


        return $this->render('paiement/paiement.html.twig', [
            'panier' => $panier,
            'paiement' => $form->createView(),
            'TotalPanier' => $totalPanier
        ]);
    }

    /**
     * @Route("/confirm", name="paiement_confirm")
     */

    public function confirm(Request $request): Response
    {
        $adresse = new Adresse;
        $user = $this->getUser();
        $panier = $this->PanierServices->getFullCart();
        $totalPanier = $this->panierService->getTotalPanier();


        $adresses = $this->entityManager->getRepository(Adresse::class)->findAll();

        if (!empty($panier['product'])) {
            return $this->redirectToRoute("home");
        }

        if (!$adresse->setUser($user)) {
            $this->addFlash('paiement_message', 'Veuillez ajouter une adresse pour continuer !');
            return $this->redirectToRoute("adresse_new");
        }

        $form = $this->createForm(PaiementType::class, ['user' => $user]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() || $this->session->get('paiement_data')) {



            if ($this->session->get('checkout_data')) {
                $data = $this->session->get('checkout_data');
            } else {
                $data = $form->getData();
                $this->session->set('checkout_data', $data);
            }




            $adresses = $form->get('adresses')->getdata();
            $transporteur =  $form->get('Transporteur')->getdata();
            $information =  $form->get('informations')->getdata();

            return $this->render('paiement/confirmation.html.twig', [
                'panier' => $panier,
                'adresse' => $adresses,
                'transporteur' => $transporteur,
                'informations' => $information,
                'TotalPanier' => $totalPanier,
                'paiement' => $form->createView()
            ]);
        }

        $adresses = $form->get('adresses')->getdata();
        $transporteur =  $form->get('Transporteur')->getdata();
        $information =  $form->get('informations')->getdata();


        return $this->render('paiement/confirmation.html.twig', [
            'panier' => $panier,
            'adresse' => $adresses,
            'transporteur' => $transporteur,
            'informations' => $information,
            'TotalPanier' => $totalPanier,
            'paiement' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit_paiement", name="paiement_edit")
     */
    public function paiementEdit(): Response
    {
        $this->session->set('paiement_data', []);
        return $this->redirectToRoute("paiement");
    }
}
