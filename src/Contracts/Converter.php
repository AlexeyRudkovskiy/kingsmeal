<?php


namespace App\Contracts;


use Knp\Component\Pager\Pagination\PaginationInterface;

abstract class Converter
{

    public abstract function convert($object): array;

    /**
     * @param array|PaginationInterface $objects
     * @return array|PaginationInterface
     */
    public function convertArray($objects)
    {
        if (is_array($objects)) {
            return $this->convertEachItem($objects);
        } else if ($objects instanceof PaginationInterface) {
            $items = $objects->getItems();
            $items = $this->convertEachItem($items);
            $objects->setItems($items);
            return $objects;
        }

        return null;
    }

    /**
     * @param array|iterable $items
     * @return array
     */
    public function convertEachItem($items): array
    {
        return array_map(function ($item) {
            return $this->convert($item);
        }, $items);
    }

}
