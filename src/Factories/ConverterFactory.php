<?php


namespace App\Factories;


use App\Entity\Category;
use App\Entity\Product;
use App\EntityConverters\CategoryConverter;
use App\EntityConverters\ProductConverter;
use App\Services\ConverterService;

class ConverterFactory
{

    public function __invoke()
    {
        $converterService = new ConverterService();

        $converterService->bind(Category::class, CategoryConverter::class);
        $converterService->bind(Product::class, ProductConverter::class);

        return $converterService;
    }

}
