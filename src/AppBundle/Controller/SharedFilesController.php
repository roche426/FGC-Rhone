<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Files;
use AppBundle\Entity\SharedFiles;
use AppBundle\Form\FilesType;
use AppBundle\Form\SharedFilesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package AppBundle\Controller
 * @Route("/admin")

 */
class SharedFilesController extends Controller
{
    /**
     * @Route("add-files", name="admin_add_files")
     */
    public function addFilesAction(Request $request)
    {
        $files = new SharedFiles();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(SharedFilesType::class, $files);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['pathFile']->getData()) {

                $uploadedFile = $files->getPathFile();
                $uploadedFileName = $files->getNameFile() . '.' .$uploadedFile->guessExtension();

                $uploadedFile->move($this->getParameter('shared_Files_Directory'), $uploadedFileName);
                $files->setPathFile($this->getParameter('shared_Files_Directory').$uploadedFileName);

                $files->setDateUpload(new \DateTime('now'));
            }

            $em->persist($files);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/addFiles.html.twig', [
                'form' => $form->createView(),
                'files' =>   $files ]
        );
    }
}
