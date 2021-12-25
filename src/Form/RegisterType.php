<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez votre prénom']
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom de famille',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez votre nom']
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez votre email']
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Mot de passe',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez votre mot de passe']

        ])



        ->add('submit', SubmitType::class, [
            'label' => 'Inscription',
            'attr' => ['class' => 'btn btn-outline-secondary btn-block']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
