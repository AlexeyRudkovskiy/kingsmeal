<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhotoType extends AbstractType
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
            ->add('imagePath', FileType::class, [ 'label' => $this->translator->trans('Image'), 'mapped' => false ])
            ->add('size', ChoiceType::class, [
                'label' => $this->translator->trans('Size'),
                'choices' => [
                    $this->translator->trans('Default') => 1,
                    $this->translator->trans('Large') => 2
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'class' => Category::class,
                'label' => $this->translator->trans('Category')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
