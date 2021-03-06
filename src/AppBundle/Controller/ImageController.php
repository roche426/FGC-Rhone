<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ImageFolders;
use AppBundle\Images\ImageManipulator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('images', FileType::class, array(
                'multiple' => true,
                'constraints' => new All(new Image(['mimeTypesMessage' => 'Format de l\'image invalide']))))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedImages = $form['images']->getData();

            foreach ($uploadedImages as $uploadedImage) {
                if ($uploadedImage) {

                    $uploadedImageName = uniqid() . '.' . $uploadedImage->guessExtension();
                    $imagePath = $folder->getPath().$uploadedImageName;

                    $imageManipulator->handleUploadedGaleryImage($uploadedImage, $imagePath);

                }
            }

            $this->addFlash('success', 'Vos images ont bien été enregistrées');
            return $this->redirectToRoute('show_gallery');
        }

        return $this->render('admin/addImages.html.twig', [
                'form' => $form->createView(),
                'folder' =>   $folder ]
        );
    }

    /**
     * @Route("show_gallery/{id}", name="show_images_gallery")
     */
    public function showImagesGalleryAction(ImageFolders $folder, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createFormBuilder($folder)
            ->add('name', TextType::class, array(
                'label' => 'Nom de l\'album',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'required' => false
            ))
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();
        }

        $images = array_diff(scandir($folder->getPath()), ['.', '..']);

        return $this->render('admin/showImagesGallery.html.twig', array(
            'folder' => $folder,
            'images' => $images,
            'editForm' => $editForm->createView()
        ));

    }

    /**
     * @Route("show_gallery/delete/{image}/{id}", name="delete_images_gallery")
     */
    public function deleteImagesGalleryAction($image, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $folder = $em->getRepository(ImageFolders::class)->find($id);

        unlink($folder->getPath().$image);

        if (!array_diff(scandir($folder->getPath()), ['.', '..'])) {
            rmdir($folder->getPath());
            unlink($this->getParameter('thematics_galery_Directory') .$folder->getImage());

            $em->remove($folder);
            $em->flush();

            return $this->redirectToRoute('show_gallery');

        }

        return $this->redirectToRoute('show_images_gallery', ['id' => $id]);

    }
}
