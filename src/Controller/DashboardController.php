<?php

namespace App\Controller;

use App\Services\KingsMealService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DashboardController
 * @package App\Controller
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{

    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/settings", name="website_settings")
     */
    public function settings(KernelInterface $kernel, Request $request, TranslatorInterface $translator)
    {

        $settingsMap = [
            [
                'type' => CollectionType::class,
                'path' => 'phones',
                'name' => 'phones',
                'label' => $translator->trans('Phones'),
                'options' => [
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'required' => false
                ]
            ], [
                'type' => TextType::class,
                'path' => 'address',
                'name' => 'address',
                'label' => $translator->trans('Address')
            ], [
                'type' => TextType::class,
                'path' => 'delivery.workTime',
                'name' => 'workTime',
                'label' => $translator->trans('Work Time')
            ], [
                'type' => TextType::class,
                'path' => 'delivery.price',
                'name' => 'price',
                'label' => $translator->trans('Delivery Price'),
                'options' => [
                    'label' => $translator->trans('Delivery Price')
                ]
            ], [
                'type' => TextType::class,
                'path' => 'delivery.time',
                'name' => 'time',
                'label' => $translator->trans('Time')
            ], [
                'type' => TextareaType::class,
                'path' => 'cardInstructions',
                'name' => 'cardInstructions',
                'label' => $translator->trans('Card Payment'),
                'options' => [
                    'label' => $translator->trans('Card Payment')
                ]
            ], [
                'type' => TextareaType::class,
                'path' => 'forosText',
                'name' => 'forosText',
                'label' => $translator->trans('Реклама Фороса'),
                'options' => [
                    'label' => $translator->trans('Реклама Фороса')
                ]
            ],
        ];

        $path = $kernel->getProjectDir() . '/var/settings.json';
        $settings = json_decode(file_get_contents($path), true);

        $form = $this->createFormBuilder($this->buildFormData($settingsMap, $settings));

        foreach ($settingsMap as $item) {
            $form = $form->add($item['name'], $item['type'], $item['options'] ?? []);
        }

        $form = $form->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'button'
            ],
            'label' => $translator->trans('Save')
        ]);

        /** @var FormInterface $form */
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            foreach ($settingsMap as $item) {
                $this->setArrayItem($settings, $item['path'], $formData[$item['name']]);
            }

            file_put_contents($path, json_encode($settings));
        }

        return $this->render('dashboard/settings/index.html.twig', [
            'settings' => $settings,
            'form' => $form->createView()
        ]);
    }

    protected function buildFormData(array $settingsMap, array $settings) {
        $data = [];

        foreach ($settingsMap as $item) {
            $key = $item['name'];
            $path = $item['path'];
            $value = $this->getArrayItem($settings, $path);

            $data[$key] = $value;
        }

        return $data;
    }

    protected function getArrayItem(array $input, string $key) {
        $key = explode('.', $key);
        $key = array_reverse($key);
        $value = $input;

        while (count($key) > 0) {
            $nextKey = array_pop($key);
            if (array_key_exists($nextKey, $value)) {
                $value = $value[$nextKey];
            } else {
                break;
            }
        }

        return $value;
    }

    public function setArrayItem(array &$input, $key, $value)
    {
        $key = explode('.', $key);
        $lastKey = array_pop($key);
        $temp = &$input;

        if (empty($key)) {
            $input[$lastKey] = $value;
        } else {
            while (count($key) > 0) {
                $_key = array_pop($key);
                $temp = &$temp[$_key];
            }

            $temp[$lastKey] = $value;
        }
    }

}
