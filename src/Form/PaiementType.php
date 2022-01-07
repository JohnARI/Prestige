<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user =$options['user'];
        $builder
            ->add('adresses', EntityType::class,[
                'label' => 'Adresse: ',
                'class'=> Adresse::class,
                'required'=>true, //oblige l'utitlisateur à choisir une adresse
                'multiple' => false, // un seul choix
                'expanded' => true, // pas obligatoire, juste pour le style

            ])
            ->add('Transporteur', EntityType::class,[
                'label' => 'Transporteur: ',
                'class'=> Transporteur::class,
                'required'=>true, //oblige l'utitlisateur à choisir une livraison
                'multiple' => false, // un seul choix
                'expanded' => true, // pas obligatoire, juste pour le style

            ])
            ->add('informations',TextareaType::class,[
                'required'=>false,
            ])

            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-light'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user'=> array(),
        ]);
    }
}