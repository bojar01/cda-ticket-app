<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Status;
use App\Entity\Ticket;
use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TicketEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technology', EntityType::class, [
                'class' => Technology::class,
                'choice_label' => 'name',
            ])
            ->add('subject')
            ->add('priority', null, [
                'label' => 'Je suis bloqué et je ne peux plus avancer'
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
            ])
            ->add('angel', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
            ])
            ->add('image', FileType::class, [
                'label' => 'Photo de l’article',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Image trop lourde',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
