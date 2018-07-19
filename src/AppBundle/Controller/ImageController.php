<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\ImageFolders;
use AppBundle\Form\ImageType;
use AppBundle\Images\ImageManipulator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package AppBundle\Controller
 * @Route("admin/")
 */
class ImageController extends Controller
{
    /**
     * @Route("add-images/{id}", name="add_images")
     */
    public function addFolderAction(Request $request, ImageManipulator $imageManipulator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $folder = $em->getRepository(ImageFolders::class)->findOneBy(['id' => $id]);

        $form = $this->createFormBuilder()
            ->add('name', FileType::class, ['multiple' => true])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedImages = $form['name']->getData();

            foreach ($uploadedImages as $uploadedImage) {
                if ($uploadedImage) {

                    $uploadedImageName = uniqid() . '.' . $uploadedImage->guessExtension();
                    $imagePath = $folder->getPath().$uploadedImageName;

                    $imageManipulator->handleUploadedGaleryImage($uploadedImage, $imagePath);

                }
            }

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/addImages.html.twig', [
                'form' => $form->createView(),
                'folder' =>   $folder ]
        );
    }

}
