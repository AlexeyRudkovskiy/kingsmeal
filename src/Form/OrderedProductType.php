<?php

namespace App\Form;

use App\Entity\OrderedProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderedProductType extends AbstractType
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
            ->add('price', null, [ 'label' => $this->translator->trans('Price') ])
            ->add('quantity', null, [ 'label' => $this->translator->trans('Quantity') ])
            ->add('product', null, [ 'label' => $this->translator->trans('Product') ])
            ->add('variant', null, [ 'label' => $this->translator->trans('Variant') ])
            ->add('_order', null, [ 'label' => $this->translator->trans('Order') ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderedProduct::class,
        ]);
    }
}
