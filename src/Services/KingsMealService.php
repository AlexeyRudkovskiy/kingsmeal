<?php


namespace App\Services;


use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\KernelInterface;

class KingsMealService
{

    /** @var ProductRepository */
    private $productRepository;
    /** @var OrderRepository */
    private $ordersRepository;
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var string */
    private $settingsPath;

    /** @var int  */
    private $productsCount = -1;

    /** @var int  */
    private $ordersCount = -1;

    /** @var int  */
    private $unprocessedOrdersCount = -1;

    /** @var int  */
    private $categoriesCount = -1;

    /** @var array */
    private $settings = null;

    public function __construct(
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        CategoryRepository $categoryRepository,
        string $settingsPath
    )
    {
        $this->productRepository = $productRepository;
        $this->ordersRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->settingsPath = $settingsPath;
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

        return $this->unprocessedOrdersCount;
    }

    public function categoriesCount()
    {
        if ($this->categoriesCount < 0) {
            $this->categoriesCount = $this->categoryRepository->getAllCount();
        }

        return $this->categoriesCount;
    }

    public function getSettings()
    {
        if ($this->settings === null) {
            $this->settings = json_decode(file_get_contents($this->settingsPath), true);
        }

        return $this->settings;
    }

    public function getSetting(string $path)
    {
        $keys = explode('.', $path);
        $value = $this->getSettings();

        foreach ($keys as $key) {
            if (!array_key_exists($key, $value)) {
                return null;
            }

            $value = $value[$key];
        }

        return $value;
    }

}
