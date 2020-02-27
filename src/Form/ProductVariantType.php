<?php

namespace App\Form;

use App\Entity\ProductVariant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductVariantType extends AbstractType
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
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('Name')
            ])
            ->add('price', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'step' => 0.01
                ],
                'label' => $this->translator->trans('Price')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class,
        ]);
    }
}
