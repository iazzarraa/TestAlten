<?php

namespace App\Form;

use App\Dto\ProductInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', FormType\TextType::class)
            ->add('name', FormType\TextType::class)
            ->add('description', FormType\TextareaType::class)
            ->add('image', FormType\TextType::class)
            ->add('category', FormType\TextType::class)
            ->add('price', FormType\NumberType::class)
            ->add('quantity', FormType\IntegerType::class)
            ->add('internalReference', FormType\TextType::class)
            ->add('shellId', FormType\IntegerType::class)
            ->add('inventoryStatus', FormType\ChoiceType::class, [
                'choices' => [
                    'In Stock' => 'INSTOCK',
                    'Low Stock' => 'LOWSTOCK',
                    'Out of Stock' => 'OUTOFSTOCK'
                ]
            ])
            ->add('rating', FormType\NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductInput::class,
            'csrf_protection' => false,
        ]);
    }
}
