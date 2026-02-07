<?php

namespace App\Form;

use App\Entity\CaseEntity;
use App\Entity\CaseImage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaseImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageUrl')
            ->add('altText')
            ->add('sortOrder')
            ->add('createdAt')
            ->add('case', EntityType::class, [
                'class' => CaseEntity::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaseImage::class,
        ]);
    }
}
