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
     */
    public function addFilesAction(Request $request)
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
    }


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
