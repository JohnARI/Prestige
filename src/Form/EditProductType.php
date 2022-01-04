<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class EditProductType extends AbstractType
{





    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez le nom du produit']
            ])

            ->add('picture', TextType::class, [
                'required' => false,
                'label' => 'Photo',
                'attr' => [
                    'required' => false
                ]
            ])


            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entrez la description du produit'],
                'constraints' => [

                    new NotBlank([

                        'message' => 'Ce champs ne peut être vide'
                    ]),

                    new Length([

                        'min' => 3,
                        'max' => 5000,
                        'minMessage' => 'Le sous-titre doit comporter {{Limit} caractères au minimum.'
                    ])


                ]
            ])

            ->add('bestseller', CheckboxType::class, [
                'label'    => 'Is best Seller',
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-warning d-block mx-auto col-4 my-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
