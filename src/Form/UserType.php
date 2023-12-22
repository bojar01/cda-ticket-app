<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles',ChoiceType::class, [
                'choices' => [
                    'Etudiant' => 'ROLE_STUDENT',
                    'Prof' => 'ROLE_COACH',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            // ->add('password')
            ->add('firstname', TextType::class , ["label" => "Prénom"])
            ->add('lastname', TextType::class , ["label" => "Nom"])
            ->add('authorized')
            // ->add('created_at')
            // ->add('updated_at')
            ->add('session', EntityType::class, [
                'class' => Session::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
