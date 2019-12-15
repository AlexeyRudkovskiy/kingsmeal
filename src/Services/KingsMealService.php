<?php


namespace App\Services;


use App\Repository\ProductRepository;

class KingsMealService
{

    /** @var ProductRepository */
    private $productRepository;

    private $productsCount = -1;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function productsCount()
    {
        if ($this->productsCount < 0) {
            $this->productsCount = $this->productRepository->getAllCount();
        }
        return $this->productsCount;
    }

}