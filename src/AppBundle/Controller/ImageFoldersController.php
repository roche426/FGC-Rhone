<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ImageFolders;
use AppBundle\Form\ImageFoldersType;
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
    public function addFolderAction(Request $request)
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

            $em->persist($folder);
            $em->flush();

            $folderId = $folder->getId();

            return $this->redirectToRoute('add_images', ['id' => $folderId]);
        }

        return $this->render('admin/addFolder.html.twig', [
                'form' => $form->createView(),
                'folder' =>   $folder ]
        );
    }

}
