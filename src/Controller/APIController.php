<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\AutoResponse;
use App\Services\ConverterService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * Class APIController
 * @package App\Controller
 * @Route("/api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/categories", name="api_categories")
     */
    public function index(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ConverterService $converterService,
        Request $request,
        PaginatorInterface $paginator,
        AutoResponse $autoResponse
    )
    {
        /** @var PaginationInterface $products */
        $products = $productRepository->getPaginated($paginator, $request);
        $products = $converterService->get(Product::class)->convertArray($products);

        return $this->json($autoResponse->format($products));

        return $this->json($converterService->get(Category::class)->convertArray($categoryRepository->findAll()));

//        /** @var Serializer $serializer */
//        $serializer = $this->get('serializer');
//        $serializedData = $serializer->serialize($categoryRepository->findAll(), 'json', ['ignored_attributes' => ['products']]);
//
//        return JsonResponse::fromJsonString($serializedData);
    }
}
