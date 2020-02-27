<?php


namespace App\EntityConverters;


use App\Contracts\Converter;
use App\Entity\Category;
use App\Entity\Product;

class CategoryConverter extends Converter
{

    /**
     * @param Category $object
     * @return array
     */
    public function convert($object): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'image' => $object->getImageUrl()
        ];
    }

}
