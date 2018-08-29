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
 * @Route("/user/files/")

 */
class FilesController extends Controller
{
    /**
     * @Route("add-files", name="add_files")
     *
     */
    public function ContactAction(Request $request)
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


    /*public function addFilesAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(Files::class)->findOneBy(['user' => $this->getUser()]);

        if (!$files) {
            return $this->redirectToRoute('first_connexion');
        }

        $currentIdCard = $files->getIdCard();

        $form = $this->createForm(FilesType::class, $files);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['idCard']->getData()) {

                $idCard = $files->getIdCard();
                $idCardName = $this->getUser()->getLastName().substr($this->getUser()->getFirstName(),0,1) . '-IdCard.' .$idCard->guessExtension();

                $idCard->move($this->getParameter('IdCard_Directory'), $idCardName);
                $files->setIdCard($this->getParameter('IdCard_Directory').$idCardName);

                if ($currentIdCard) {
                    unlink($currentIdCard);
                }
            }

            $em->persist($files);
            $em->flush();

            return $this->redirectToRoute('member_area');
        }

        return $this->render('user/editFiles.html.twig', [
                'form' => $form->createView(),
                'files' =>   $files ]
        );
    }*/


    /**
     * @Route("download/{id}", name="download_user_files")
     */
    public function downloadFilesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(Files::class)->findOneBy(['user' => $id]);

        return $this->file($files->getIdCard());

    }
}
