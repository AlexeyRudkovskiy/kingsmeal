<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProdController extends AbstractController
{
    /**
     * @Route("/", name="prod")
     */
    public function index()
    {
        /** @var ProductRepository $products */
        $products = $this->getDoctrine()->getRepository(Product::class);
        $users = $this->getDoctrine()->getRepository(User::class);
        $first = $users->find(2);
        $first->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->getDoctrine()->getManager()->persist($first);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('prod/index.html.twig', [
            'controller_name' => 'ProdController',
            'products' => $products->findAll()
        ]);
    }
}
