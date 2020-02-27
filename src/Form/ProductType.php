<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
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
            ->add('name', TextType::class, [ 'label' => $this->translator->trans('Name') ])
            ->add('price', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'step' => 0.01
                ],
                'label' => $this->translator->trans('Price')
            ])
            ->add('description', TextareaType::class, [ 'label' => $this->translator->trans('Description') ])
            ->add('photoFilename', FileType::class, [
                'label' => $this->translator->trans('Photo'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => $this->translator->trans('You should upload only images (JPG or PNG)'),
                    ])
                ]
            ])
            ->add('categories', EntityType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'class' => Category::class,
                'label' => $this->translator->trans('Categories')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
