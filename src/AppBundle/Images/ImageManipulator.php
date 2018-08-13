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
    private $thematicsGaleryDirectory;

    public function __construct(SimpleImage $simpleImage, $profilUploadPath, $thematicsGaleryDirectory)
    {
        $this->simpleImage = $simpleImage;
        $this->profilUploadPath = $profilUploadPath;
        $this->thematicsGaleryDirectory = $thematicsGaleryDirectory;
    }

    /**
     * Upload and resize of profil picture
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
     * Upload and resize of gallery picture
     */
    public function handleUploadedGaleryImage($picture, $fileNamePicture)
    {
        if (isset($picture)) {
            $this->simpleImage
            ->fromFile($picture->getRealPath())
            ->toFile($fileNamePicture);
        }
    }

    /**
     * Upload and resize of thematic gallery picture
     */
    public function handleUploadedThematicGaleryImage($picture, $fileNamePicture)
    {
        if (isset($picture)) {
            $this->simpleImage
            ->fromFile($picture->getRealPath())
            ->resize(500,500)
            ->toFile($this->thematicsGaleryDirectory.$fileNamePicture);
        }
    }

}
