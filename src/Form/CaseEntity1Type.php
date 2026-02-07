<?php

namespace App\Form;

use App\Entity\CaseEntity;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaseEntity1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('location')
            ->add('urgency')
            ->add('status')
            ->add('cha9a9aUrl')
            ->add('targetAmount')
            ->add('currentAmount')
            ->add('viewsCount')
            ->add('isFeatured')
            ->add('publishedAt')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaseEntity::class,
        ]);
    }
}
