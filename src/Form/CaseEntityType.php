<?php

namespace App\Form;

use App\Entity\CaseEntity;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class CaseEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // category_id
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '— Choisir une catégorie —',
                'required' => true,
            ])

            // title
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Aide médicale urgent…'],
            ])

            // description
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [new NotBlank()],
                'attr' => ['rows' => 4, 'placeholder' => 'Décrire le cas…'],
            ])

            // location
            ->add('location', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Tunis, Sfax…'],
            ])

            // urgency (varchar 20)
            ->add('urgency', ChoiceType::class, [
                'required' => false,
                'placeholder' => '— Urgence —',
                'choices' => [
                    'LOW' => 'LOW',
                    'MEDIUM' => 'MEDIUM',
                    'HIGH' => 'HIGH',
                ],
            ])

            // status (varchar 20)
            ->add('status', ChoiceType::class, [
                'required' => false,
                'placeholder' => '— Status —',
                'choices' => [
                    'DRAFT' => 'DRAFT',
                    'PUBLISHED' => 'PUBLISHED',
                    'CLOSED' => 'CLOSED',
                ],
            ])

            // ✅ cha9a9a_url NOT NULL
            ->add('cha9a9aUrl', UrlType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Url(),
                ],
                'attr' => ['placeholder' => 'Lien de la preuve (image/document)'],
                'label' => 'Preuve (URL)',
            ])

            ->add('stripeUrl', UrlType::class, [
                'required' => false,
                'constraints' => [
                    new Url(),
                ],
                'attr' => ['placeholder' => 'https://buy.stripe.com/...'],
                'label' => 'Lien Stripe (Donation)',
            ])

            // target_amount decimal(12,2)
            ->add('targetAmount', MoneyType::class, [
                'required' => true,
                'currency' => 'TND',
                'constraints' => [new NotBlank()],
            ])

            // current_amount decimal(12,2)
            ->add('currentAmount', MoneyType::class, [
                'required' => false,
                'currency' => 'TND',
                'empty_data' => '0',
            ])

            // views_count int
            ->add('viewsCount', IntegerType::class, [
                'required' => false,
                'empty_data' => '0',
                'attr' => ['min' => 0],
            ])

            // is_featured tinyint
            ->add('isFeatured', CheckboxType::class, [
                'required' => false,
            ])

            // published_at datetime
            ->add('publishedAt', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
        ;
        // ⚠️ created_at / updated_at / created_by => نعبّيوهم في controller (مش في الفورم)
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaseEntity::class,
        ]);
    }
}
