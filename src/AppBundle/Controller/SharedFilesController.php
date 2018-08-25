<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SharedFiles;
use AppBundle\Form\SharedFilesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @package AppBundle\Controller
 * @Route("/admin")

 */
class SharedFilesController extends Controller
{
    /**
     * @Route("/add-files", name="admin_add_files")
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
     * @Route("/edit-shared-files/{id}", name="admin_edit_files")
     */
    public function editFilesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(SharedFiles::class)->find($id);

        $editForm = $this->createFormBuilder($files)
            ->add('subject', TextType::class, array(
                'label' => 'Sujet',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('description', TextType::class,  array(
                'label' => 'Description',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('nameFile', TextType::class,  array(
                'label' => 'Nom du fichier',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('fileAccess', ChoiceType::class, array(
                'choices' => [
                    'Tout le monde' => SharedFiles::PUBLIC_ACCESS_FILE,
                    'Membres' => SharedFiles::MEMBERS_ACCESS_FILE,
                    'Membres du bureau' => SharedFiles::BUREAU_MEMBERS_ACCESS_FILE,
                    'Administrateur' => SharedFiles::ADMIN_ACCESS_FILE
                ],
                'label' => 'Droits d\'accès',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('isShared', ChoiceType::class, array(
                'choices' => [
                    'Partager plus tard' => false,
                    'Partager maintenant' => true
                ],
                'label' => 'Partage du fichier',
                'constraints' => new NotNull(['message' => 'Ce champs ne doit pas être nul'])))
            ->getForm();


        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($files);
            $em->flush();

            return $this->redirectToRoute('admin_show_files');
        }

        return $this->render('admin/editSharedFiles.html.twig', ['editForm' => $editForm->createView()]);
    }


    /**
     * @Route("/shared-files", name="admin_show_files")
     */
    public function listFilesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(SharedFiles::class)->findAll();

        return $this->render('admin/listSharedFiles.html.twig', ['files' => $files]);
    }

    /**
     * @Route("/download/{id}", name="admin_download_files")
     */
    public function downloadFilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(SharedFiles::class)->findOneBy(['id' => $id]);

        return $this->file($files->getPathFile());
    }

    /**
     * @Route("/delete/{id}", name="admin_delete_files")
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
