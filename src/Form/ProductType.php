<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

            ->add('movement', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez le type de mouvement']
            ])



            ->add('braceletType', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez le type de bracelet']
            ])

            ->add('strapColor', TextType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez la couleur du bracelet']
            ])

            ->add('picture', TextType::class)
            
            ->add('price', MoneyType::class, [
                'label' => 'Produit',
                'attr' => ['placeholder' => 'Entrez un prix']
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-dark']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
