<?php


namespace App\Contracts;


use Symfony\Component\HttpFoundation\Request;

interface WithUpladableFile
{

    public function getFileFieldName();

    /**
     * @param $object
     * @return string
     */
    public function getCurrentLocation($object);

    public function updateCurrentLocation(string $path, $object);

    public function getEntityTypeIdentifier(): string;

}
