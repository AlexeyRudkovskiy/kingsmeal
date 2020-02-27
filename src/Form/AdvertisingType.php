<?php

namespace App\Form;

use App\Contracts\WithUpladableFile;
use App\Entity\Advertising;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdvertisingType extends AbstractType
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
            ->add('title', TextType::class, [ 'label' => $this->translator->trans('Title') ])
            ->add('description', TextType::class, [ 'label' => $this->translator->trans('Description') ])
            ->add('fileName', FileType::class, [ 'label' => $this->translator->trans('Image'), 'mapped' => false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advertising::class,
        ]);
    }

}
