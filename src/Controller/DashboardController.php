<?php

namespace App\Controller;


use App\Entity\User;
use TransporteurType;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Form\EditUserType;
use App\Form\RegisterType;
use App\Entity\Transporteur;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DashboardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, PanierService $panierService)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->panierService = $panierService;
    }

    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(): Response{

        $totalPanier = $this->panierService->getTotalPanier();
    
        $products = $this->entityManager->getRepository(Product::class)->findAll();
    
        return $this->render("dashboard/dashboard.html.twig",[

            'products'=> $products,
            'TotalPanier'=>$totalPanier

        ]);
    }

    /**
     * @Route("/admin/dashboard/user", name="showUser")
     */
    public function showUser(): Response
    {

        $totalPanier = $this->panierService->getTotalPanier();

        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('dashboard/user.html.twig', [

            'users' => $users,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/admin/dashboard/product", name="showProduct")
     */
    public function showProduct(): Response
    {

        $totalPanier = $this->panierService->getTotalPanier();

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('dashboard/product.html.twig', [

            'products' => $products,
            'TotalPanier'=>$totalPanier
        ]);
    }

    /**
     * @Route("/admin/dashboard/category", name="showCategory")
     */
    public function showCatagory(): Response
    {

        $totalPanier = $this->panierService->getTotalPanier();

        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('dashboard/category.html.twig', [

            'categories' => $categories,
            'TotalPanier'=>$totalPanier
        ]);
    }



    /**
     * @Route("/admin/add/user", name="add_user")
     */
    public function addUser(Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        $totalPanier = $this->panierService->getTotalPanier();

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_user.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/user/{id}", name="edit_user")
     */
    public function editUser($id, Request $request): Response
    {

        $totalPanier = $this->panierService->getTotalPanier();

        $users = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(EditUserType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users->setPassword($this->passwordHasher->hashPassword($users, $users->getPassword()));
            $this->entityManager->persist($users);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/edit_user.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete/user/{id}", name="delete_user")
     */
    public function deleteUser(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        

        $this->addFlash('success', 'Utilisateur supprimé !');

        return $this->redirectToRoute('dashboard');
    }


    /**
     * @Route("admin/add/category", name="add_category")
     */
    public function addCategory(Request $request): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $totalPanier = $this->panierService->getTotalPanier();

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_category.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/category/{id}", name="edit_category")
     */
    public function editCategory($id, Request $request): Response
    {

        $categories = $this->entityManager->getRepository(Category::class)->find($id);

        $totalPanier = $this->panierService->getTotalPanier();

        $form = $this->createForm(CategoryType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categories);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }




        return $this->render('dashboard/edit_category.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/category/{id}", name="delete_category")
     */
    public function deleteCategory(Category $categories, Request $request): Response
    {

        $this->entityManager->remove($categories);
        $this->entityManager->flush();
        $this->addFlash('success', 'Membre supprimé !');





        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }


     /**
     * @Route("/admin/dashboard/transporteur", name="showTransporteur")
     */
    public function showTransporteur(): Response
    {

        $totalPanier = $this->panierService->getTotalPanier();

        $transporteurs = $this->entityManager->getRepository(transporteur::class)->findAll();

        return $this->render('dashboard/transporteur.html.twig', [

            'transporteurs' => $transporteurs,
            'TotalPanier'=>$totalPanier,
            
        ]);
    }

    /**
     * @Route("admin/add/transporteur", name="add_transporteur")
     */
    public function addTransporteur(Request $request): Response
    {

        $transporteurs = new Transporteur();
        $form = $this->createForm(TransporteurType::class, $transporteurs);
        $form->handleRequest($request);
        $totalPanier = $this->panierService->getTotalPanier();

        if ($form->isSubmitted() && $form->isValid()) {

            $transporteurs = $form->getData();
            $this->entityManager->persist($transporteurs);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_transporteur.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView(),
        ]);
    }


     /**
     * @Route("/admin/edit/transporteur/{id}", name="edit_transporteur")
     */
    public function editTransporteur($id, Request $request): Response
    {

        $transporteurs = $this->entityManager->getRepository(Transporteur::class)->find($id);

        $totalPanier = $this->panierService->getTotalPanier();

        $form = $this->createForm(TransporteurType::class, $transporteurs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($transporteurs);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }




        return $this->render('dashboard/edit_transporteur.html.twig', [

            'TotalPanier'=>$totalPanier,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/delete/transporteur/{id}", name="delete_transporteur")
     */
    public function deleteTransporteur(Transporteur $transporteurs, Request $request): Response
    {

        $this->entityManager->remove($transporteurs);
        $this->entityManager->flush();
        $this->addFlash('success', 'Transporteur supprimé !');





        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }
    
}
