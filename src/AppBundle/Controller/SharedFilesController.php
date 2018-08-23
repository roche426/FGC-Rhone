<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SharedFiles;
use AppBundle\Form\SharedFilesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
                $uploadedFileName = $files->getNameFile() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move($this->getParameter('shared_Files_Directory'), $uploadedFileName);
                $files->setPathFile($this->getParameter('shared_Files_Directory') . $uploadedFileName);

                $files->setDateUpload(new \DateTime('now'));
            }

            $em->persist($files);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/addSharedFiles.html.twig', [
                'form' => $form->createView(),
                'files' => $files
            ]
        );
    }

    /**
     * @Route("shared-files", name="admin_show_files")
     */
    public function listFilesAction()
    {
        $em = $this->getDoctrine()->getRepository(SharedFiles::class);
        $files = $em->findAll();

        return $this->render('admin/listSharedFiles.html.twig', ['files' => $files]);
    }

    /**
     * @Route("download/{id}", name="admin_download_files")
     */
    public function downloadFilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(SharedFiles::class)->findOneBy(['id' => $id]);

        return $this->file($files->getPathFile());
    }

    /**
     * @Route("delete/{id}", name="admin_delete_files")
     */
    public function deleteFilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(SharedFiles::class)->findOneBy(['id' => $id]);

        if ($files->getPathFile()) {
            unlink($files->getPathFile());

            $em->remove($files);
            $em->flush();

            return $this->redirectToRoute('admin_show_files');
        }

        return $this->redirectToRoute('admin_show_files');
    }
}
