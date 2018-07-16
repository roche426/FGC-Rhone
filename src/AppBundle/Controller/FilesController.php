<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Files;
use AppBundle\Form\FilesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function addFiles(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository(Files::class)->findOneBy(['user' => $this->getUser()]);

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
}
