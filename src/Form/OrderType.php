<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{

    /** @var TranslatorInterface */
    protected $translator = null;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod', null, [ 'label' => $this->translator->trans('Payment Method') ])
            ->add('address', null, [ 'label' => $this->translator->trans('Address') ])
            ->add('houseNumber', null, [ 'label' => $this->translator->trans('House Number') ])
            ->add('apartment', null, [ 'label' => $this->translator->trans('Apartment') ])
            ->add('firstName', null, [ 'label' => $this->translator->trans('First Name') ])
            ->add('lastName', null, [ 'label' => $this->translator->trans('Last Name') ])
            ->add('phoneNumber', null, [ 'label' => $this->translator->trans('Phone Number') ])
            ->add('products', CollectionType::class, [
                'entry_type' => OrderedProductType::class,
                'prototype' => true,
                'allow_add' => true,
                'label' => $this->translator->trans('Products')
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
