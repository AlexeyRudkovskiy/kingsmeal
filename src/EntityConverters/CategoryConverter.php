<?php


namespace App\EntityConverters;


use App\Contracts\Converter;
use App\Entity\Category;

class CategoryConverter extends Converter
{

    /**
     * @param Category $object
     * @return array
     */
    public function convert($object): array
    {
        return [
            'name' => $object->getName()
        ];
    }

}
