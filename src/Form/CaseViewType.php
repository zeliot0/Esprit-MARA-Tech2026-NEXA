<?php

namespace App\Form;

use App\Entity\CaseEntity;
use App\Entity\CaseView;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaseViewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipHash')
            ->add('userAgent')
            ->add('viewedAt')
            ->add('case', EntityType::class, [
                'class' => CaseEntity::class,
                'choice_label' => 'id',
            ])
            ->add('viewer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaseView::class,
        ]);
    }
}
