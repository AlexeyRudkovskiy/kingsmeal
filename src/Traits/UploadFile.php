<?php


namespace App\Traits;


use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadFile
{


    /** todo: move this code to service */
    /**
     * @param $object
     * @param \Symfony\Component\Form\FormInterface $form
     */
    public function uploadPhoto($object, \Symfony\Component\Form\FormInterface $form): void
    {
        /** @var UploadedFile $previewFile */
        $previewFile = $form[$this->getFileFieldName()]->getData();

        if ($previewFile !== null) {
            $directory = $this->getParameter('previews_directory');
            $this->removeImageIfExists($object);
            $originalName = pathinfo($previewFile->getClientOriginalName(), PATHINFO_FILENAME);
            $hashContent = $this->getEntityTypeIdentifier() . $originalName;
            $newFilename = sha1($hashContent) . '-' . uniqid() . '.' . $previewFile->guessExtension();

            $previewFile->move($directory, $newFilename);

            $this->updateCurrentLocation($newFilename, $object);
        }
    }

    /**
     * @param $object
     */
    private function removeImageIfExists($object) {
        $photoFilename = $this->getCurrentLocation($object);
        $directory = $this->getParameter('previews_directory');
        $absoluteFilePath = $directory . '/' . $photoFilename;

        if (is_file($absoluteFilePath)) {
            unlink($absoluteFilePath);
        }
    }

}
