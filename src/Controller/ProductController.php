<?php

namespace App\Controller;






use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/product", name="add_product")
     * @param Request $request
     * @return Response
     */
    public function createProduct(Request $request, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            # Association de l'article au user : setOwner()
            //

            # Association de l'article à la category : setOwner()
            //

            # Coder ici la logique pour uploader la photo
            // On récupère le fichier du formulaire grâce à getData(). Cela nous retourne un objet de type UploadedFile.
            $file = $form->get('picture')->getData();

            // Condition qui vérifie si un fichier est présent dans le formulaire.
            if ($file) {

                // Générer une contrainte d'upload. On déclare un array avec deux valeurs de type string qui sont les MimeType autorisés.
                // $allowedMimeType = ['image/jpeg', 'image/png'];
                // La fonction native in_array() permet de comparer deux valeurs (2 arguments attendus)
                // if(in_array($file->getMimeType(),$allowedMimeType)) {

                # Nous allons construire le nouveau nom du fichier

                // On stock dans une variable $originalFilename le nom du fichier.
                // On utilise encore une fonction native pathinfo()
                // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                # Récupération de l'extension pour pouvoir reconstruire le nom quelque lignes après
                // On utilise la concaténation pour ajouter un point '.'
                $extension = '.' . $file->guessExtension();

                # Assainissement du nom grâce au slugger fourni par symfony pour la construction du nouveau nom
                $safeFilename = $slugger->slug($product->getName());
                // $safeFilename = $slugger->slug($originalFilename);


                # Construction du nouveau nom
                // uniqid() est une fonctiion native qui permet de gerer un ID unique
                $newFilename = $safeFilename . '-' . uniqid() . $extension;

                try {
                    /* On appelle la méthode move() de uploaded file pour pouvoir déplacer le fichier dans son dossier de déstination.
                        Le dossier de déstination a été parametré dans services.yaml 

                        /!\ ATTENTION:
                            La méthode move() lance une erreur de type FileException.
                            On attrape cette erreur dans le catch(FileException $exception)
                         */
                    $file->move($this->getParameter('uploads_dir'), $newFilename);

                    // On set 

                    $product->setPicture($newFilename);
                } catch (FileException $exception) {
                    // Code à executer si une erreur est attrapée
                }

                // } 
                // else { // Si ce n'est pas le bon type de fichier uploadé, alors on affiche le message et on redirige
                //     $this->addFlash('warning', 'Les typpes de fichier autorisés sont : jpeg / .png'); // Affiche le message
                //     return $this->redirectToRoute('create_article'); // Redirige vers la création d'article
                // }
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit ajouter!');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/product/{id}", name="edit_product")
     * @param Product $article
     * @param Request $request
     * @return Response
     */
    public function editProduct(Product $product, Request $request): Response
    {
        # Supprimer le edit form et utiliser ProductType (configurer les options) : pas besoin de dupliquer un form
        $form = $this->createForm(ProductType::class, $product)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Créer une nouvelle propriété dans l'entité : setUpdatedAt()

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

        return $this->render('dashboard/edit_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/product/{id}", name="show_product")
     * @param Product $viewProduct
     * @return Response
     */
    public function showProduct(Product $viewProduct): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($viewProduct->getId());

        return $this->render('product/show_product.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/delete/product/{id}", name="delete_product")
     * @param Product $product
     * @return Response
     */
    public function deleteProduct(Product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success', 'Produit supprimé !');

        return $this->redirectToRoute('dashboard/product.html.twig');
    }
}
