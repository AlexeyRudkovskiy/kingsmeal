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
            'id' => $object->getId(),
            'name' => $object->getName(),
            'price' => $object->getPrice(),
            'photo' => $object->getPhotoUrl(),
            'description' => explode("\n", str_replace("\r", '', $object->getDescription())),
            'variants' => $object->getVariants()->map(function (ProductVariant $variant) {
                return [
                    'id' => $variant->getId(),
                    'name' => $variant->getName(),
                    'price' => $variant->getPrice()
                ];
            })
        ];
    }

}
