<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Form\EditUserType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DashboardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {

        return $this->render('dashboard/dashboard.html.twig', []);
    }

    /**
     * @Route("/admin/dashboard/user", name="showUser")
     */
    public function showUser(): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('dashboard/user.html.twig', [

            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/dashboard/product", name="showProduct")
     */
    public function showProduct(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('dashboard/product.html.twig', [

            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/dashboard/category", name="showCategory")
     */
    public function showCatagory(): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('dashboard/category.html.twig', [

            'categories' => $categories,
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

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/user/{id}", name="edit_user")
     */
    public function editUser($id, Request $request): Response
    {

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
            'form' => $form->createView(),
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
     * @Route("admin/add/product", name="add_product")
     */
    public function addProduct(Request $request): Response
    {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/product/{id}", name="edit_product")
     */
    public function editProduct($id, Request $request): Response
    {

        $products = $this->entityManager->getRepository(product::class)->find($id);

        $form = $this->createForm(ProductType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($products);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }




        return $this->render('dashboard/edit_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/product/{id}", name="delete_product")
     */
    public function deleteproduct(product $products, Request $request): Response
    {

        $this->entityManager->remove($products);
        $this->entityManager->flush();
        $this->addFlash('success', 'Membre supprimé !');





        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }

    /**
     * @Route("admin/add/category", name="add_category")
     */
    public function addCategory(Request $request): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/category/{id}", name="edit_category")
     */
    public function editCategory($id, Request $request): Response
    {

        $categories = $this->entityManager->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categories);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }




        return $this->render('dashboard/edit_category.html.twig', [
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
}
