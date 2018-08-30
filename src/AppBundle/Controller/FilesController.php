<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Files;
use AppBundle\Form\FilesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package AppBundle\Controller
 * @Route("/user")

 */
class FilesController extends Controller
{
    /**
     * @Route("/show-files")
     */
    public function showFilesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(Files::class)->findBy(['user' => $this->getUser()]);

        return $this->render('user/showFiles.html.twig', ['files' =>   $files ]);
    }


    /**
     * @Route("/add-files", name="add_files")
     */
    public function addFilesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('AppBundle\Form\DocumentType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            foreach ($data['files'] as $files) {

                if ($files) {
                    $idCard = $files->getIdCard();
                    $idCardName = uniqid() .$idCard->guessExtension();

                    $idCard->move($this->getParameter('IdCard_Directory'), $idCardName);
                    $files->setIdCard($this->getParameter('IdCard_Directory').$idCardName);
                    $files->setUser($this->getUser());
                }

                $em->persist($files);
                $em->flush();
            }

            if (!$data['files']) {
                $this->addFlash('danger', 'Aucun champs renseignés');
                return $this->redirectToRoute('member_area');
            }

            $this->addFlash('success', 'Vos documents ont bien été téléchargés!');
            return $this->redirectToRoute('member_area');
        }

        return $this->render('user\addFiles.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("download/{id}", name="download_user_files")
     */
    public function downloadFilesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(Files::class)->find($id);

        return $this->file($files->getIdCard());

    }
}
