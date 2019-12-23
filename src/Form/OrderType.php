<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod')
            ->add('address')
            ->add('houseNumber')
            ->add('apartment')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('products', CollectionType::class, [
                'entry_type' => OrderedProductType::class,
                'prototype' => true,
                'allow_add' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
