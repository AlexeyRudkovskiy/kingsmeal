<?php


namespace App\Services;


use App\Contracts\Converter;

class ConverterService
{

    private $map = [];

    public function bind($entityType, $converter): self
    {
        $this->map[$entityType] = $converter;

        return $this;
    }

    /**
     * @param $entityType
     * @return Converter
     * @throws \Exception
     */
    public function get($entityType): Converter
    {
        if (array_key_exists($entityType, $this->map)) {
            return new $this->map[$entityType]();
        }

        throw new \Exception("Unknown entity type");
    }

}
