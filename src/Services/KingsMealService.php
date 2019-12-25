<?php


namespace App\Services;


use App\Repository\OrderRepository;
use App\Repository\ProductRepository;

class KingsMealService
{

    /** @var ProductRepository */
    private $productRepository;
    /** @var OrderRepository */
    private $ordersRepository;

    /** @var int  */
    private $productsCount = -1;

    /** @var int  */
    private $ordersCount = -1;

    /** @var int  */
    private $unprocessedOrdersCount = -1;

    public function __construct(ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->ordersRepository = $orderRepository;
    }

    public function productsCount()
    {
        if ($this->productsCount < 0) {
            $this->productsCount = $this->productRepository->getAllCount();
        }
        return $this->productsCount;
    }

    public function ordersCount() {
        if ($this->ordersCount < 0) {
            $this->ordersCount = $this->ordersRepository->getAllCount();
        }

        return $this->ordersCount;
    }

    public function unprocessedOrdersCount()
    {
        if ($this->unprocessedOrdersCount < 0) {
            $this->unprocessedOrdersCount = $this->ordersRepository->getUnprocessedCount();
        }

        return $this->ordersCount;
    }

}