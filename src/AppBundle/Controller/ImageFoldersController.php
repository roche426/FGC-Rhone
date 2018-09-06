<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ImageFolders;
use AppBundle\Form\ImageFoldersType;
use AppBundle\Images\ImageManipulator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package AppBundle\Controller
 * @Route("admin/")
 */
class ImageFoldersController extends Controller
{
    /**
     * @Route("add-folder", name="add_folder")
     */
    public function addFolderAction(Request $request, ImageManipulator $imageManipulator)
    {

        $folder = new ImageFolders();
        $em = $this->getDoctrine()->getManager();


        $form = $this->createForm(ImageFoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $path = $this->getParameter('galery_Directory').$folder->getName().'/';
            $folder->setPath($path);

            if (!mkdir($path) && !is_dir($path)) {
                mkdir($path);
            }

            if ($form['image']->getData()) {

                $image = $folder->getImage();

                $imageName = $folder->getName().'.'.$image->guessExtension();

                $imageManipulator->handleUploadedThematicGaleryImage($image, $imageName);
                $folder->setImage($imageName);

            }

            $folder->setCreationDate(new \DateTime('now'));

            $em->persist($folder);
            $em->flush();

            $folderId = $folder->getId();

            $this->addFlash('success', 'Votre galerie a bien été créée');
            return $this->redirectToRoute('add_images', ['id' => $folderId]);
        }

        return $this->render('admin/addFolder.html.twig', [
                'form' => $form->createView(),
                'folder' =>   $folder ]
        );
    }


    /**
     * @Route("show_gallery", name="show_gallery")
     */
    public function showGalleryAction()
    {
        $folders = $this->getDoctrine()->getRepository(ImageFolders::class)->findAll();

        return $this->render('admin/showGallery.html.twig', array(
            'folders' => $folders
        ));

    }


    /**
     * @Route("show_gallery/delete/{id}", name="delete_folder_gallery")
     */
    public function deleteFolderGalleryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $folder = $em->getRepository(ImageFolders::class)->find($id);

        array_map('unlink', glob($folder->getPath().'*'));
        rmdir($folder->getPath());
        unlink($this->getParameter('thematics_galery_Directory').$folder->getImage());

        $em->remove($folder);
        $em->flush();

        $this->addFlash('success', 'Votre galerie a bien été supprimée');
        return $this->redirectToRoute('show_gallery');

    }

}
