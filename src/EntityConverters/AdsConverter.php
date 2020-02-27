<?php


namespace App\EntityConverters;


use App\Contracts\Converter;
use App\Entity\Advertising;

class AdsConverter extends Converter
{

    /**
     * @param Advertising $object
     * @return array
     */
    public function convert($object): array
    {
        return [
            'id' => $object->getId(),
            'image' => $object->getImageUrl(),
            'title' => $object->getTitle(),
            'description' => explode("\n", str_replace("\r", '', $object->getDescription()))
        ];
    }

}
