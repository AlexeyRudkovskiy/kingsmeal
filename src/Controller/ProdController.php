<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\AdvertisingRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProdController extends AbstractController
{

    /**
     * @Route("/")
     * @param CategoryRepository $categoryRepository
     * @param AdvertisingRepository $advertisingRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index2(
        CategoryRepository $categoryRepository,
        AdvertisingRepository $advertisingRepository
    )
    {
        return $this->render('prod/index2.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'advertisings' => $advertisingRepository->findAll()
        ]);
    }

    /**
     * @Route("/test", name="prod")
     */
    public function index()
    {
        /** @var ProductRepository $products */
        $products = $this->getDoctrine()->getRepository(Product::class);
        $users = $this->getDoctrine()->getRepository(User::class);
        $first = $users->find(1);
        $first->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->getDoctrine()->getManager()->persist($first);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('prod/index.html.twig', [
            'controller_name' => 'ProdController',
            'products' => $products->findAll()
        ]);
    }
}
