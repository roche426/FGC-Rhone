<?php
namespace AppBundle\Images;


use claviska\SimpleImage;

class ImageManipulator
{
    /**
     * @var SimpleImage
     */
    private $simpleImage;
    private $profilUploadPath;

    public function __construct(SimpleImage $simpleImage, $profilUploadPath)
    {
        $this->simpleImage = $simpleImage;
        $this->profilUploadPath = $profilUploadPath;
    }

    /**
     * Upload and resize of article picture
     */
    public function handleUploadedPicture($picture, $fileNamePicture)
    {
        if (isset($picture)) {
            $this->simpleImage
                ->fromFile($picture->getRealPath())
                ->bestFit(200, 200)
                ->toFile($this->profilUploadPath . $fileNamePicture);
        }
    }

    /**
     * Upload and resize of article picture
     */
    public function handleUploadedGaleryImage($picture, $fileNamePicture)
    {
        if (isset($picture)) {
            $this->simpleImage
            ->fromFile($picture->getRealPath())
            ->resize(400,400)
            ->toFile($fileNamePicture);
        }
    }

}
