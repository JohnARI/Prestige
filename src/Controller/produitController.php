<?php

namespace App\Controller;


use App\Entity\product;
use App\Form\productType;
use App\Form\EditproductType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class productController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/product", name="create_product")
     * @param Request $request
     * @return Response
     */
    public function createProduct(Request $request, FileUploader $file_uploader): Response
    {
        $product = new product();
        $form = $this->createForm(productType::class, $product);
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){

            $product = $form->getData();

            $product->setUser($this->getUser());

            

            # Association du produit  au user : setOwner()
            //

            # Association du produit à la category : setOwner()
            //

            $product->setCreatedAt(new \DateTime());

            # Coder ici la logique pour uploader la photo
            //

            // On récupère le fichier du formulaire grâce à getData(). Cela nous retourne un objet dt type Uploadedfile
        
            $file = $form->get('picture')->getData();//On crée une variable $file qui va récuperer les infos sur l'images à partir de getData() soumis à product

            dd($file);

            if($file) {// Si $file isset (existe) alors: condition de vérifcation du fichier $file


                $file_name = $file_uploader->upload($file);
                if (null !== $file_name) // for example

                {
                    $directory = $file_uploader->getTargetDirectory();
                    $full_path = $directory.'/'.$file_name;

                }

                else

                {

                    $this->addFlash('warning', 'Type de fichiers non autorisé');

                }





            
            }

            $this->entityManager->persist($product);//Ajoute les données en bdd
            $this->entityManager->flush();//vide la boite EntityManager

            $this->addFlash('success','product ajouter!');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form_product.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier/product/{id}", name="edit_product")
     * @param product $product
     * @param Request $request
     * @return Response
     */
    public function editproduct(product $product, Request $request): Response
    {
        # Supprimer le edit form et utiliser productType (configurer les options) : pas besoin de dupliquer un form
        $form = $this->createForm(EditproductType::class, $product)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            # Créer une nouvelle propriété dans l'entité : setUpdatedAt()

            $this->entityManager->persist($product);
            $this->entityManager->flush();

        }

        return $this->render('product/edit_product.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/voir/produit/{id}", name="show_product")
     * @param product $singleproduct
     * @return Response
     */
    public function showproduct(product $singleproduct): Response
    {
        $product = $this->entityManager->getRepository(product::class)->find($singleproduct->getId());

        return $this->render('product/show_product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/supprimer/product/{id}", name="delete_product")
     * @param product $product
     * @return Response
     */
    public function deleteproduct(product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success','product supprimé !');

        return $this->redirectToRoute('dashboard');
    }
}
