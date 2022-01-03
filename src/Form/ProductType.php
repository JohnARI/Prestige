<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez le nom du produit']
            ])

            ->add('dialColor', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez la couleur du cadran']
            ])

            ->add('movement', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez le type de mouvement']
            ])

            ->add('matter', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez lz matière']
            ])

            ->add('length', IntegerType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez la longueur']
            ])

            ->add('braceletType', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez le type de bracelet']
            ])

            ->add('strapColor', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez la couleur du bracelet'],
                'constraints' => [

                    new NotBlank([

                        'message' => 'Ce champs ne peut être vide'
                    ]),

                    new length([

                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le sous-titre doit comporter {{Limit} caractères au minimum.'
                    ])


                ]
            ])

            ->add('picture', FileType::class, [

                'label' => 'Photo',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez une illustration'],
                'constraints'=> [

                    new Image([
                        'mimeTypes' => ['images/jpeg', 'images/png', 'images/webp' ,'images/jpg'],
                        'mimeTypesMessage' => 'Les types de fichiers autorisés sont : .jpeg / .png / .webp / .jpg'
                    ])
                ]



            ])
            
            ->add('price', MoneyType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez un prix']
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-dark']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'allow_file_upload' => true,
        ]);
    }
}
