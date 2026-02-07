<?php

namespace App\Form;

use App\Entity\CaseEntity;
use App\Entity\Donation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('currency')
            ->add('donorName')
            ->add('donorEmail')
            ->add('status')
            ->add('note')
            ->add('createdAt')
            ->add('case', EntityType::class, [
                'class' => CaseEntity::class,
                'choice_label' => 'id',
            ])
            ->add('donor', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
