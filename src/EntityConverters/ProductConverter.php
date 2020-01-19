<?php


namespace App\EntityConverters;


use App\Contracts\Converter;
use App\Entity\Product;
use App\Entity\ProductVariant;

class ProductConverter extends Converter
{

    /**
     * @param Product $object
     * @return array
     */
    public function convert($object): array
    {
        return [
            'name' => $object->getName(),
            'price' => $object->getPrice(),
            'variants' => $object->getVariants()->map(function (ProductVariant $variant) {
                return [
                    'name' => $variant->getName(),
                    'price' => $variant->getPrice()
                ];
            })
        ];
    }

}
