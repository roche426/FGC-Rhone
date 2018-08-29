<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Files;
use AppBundle\Entity\User;
use AppBundle\Form\DocumentType;
use AppBundle\Form\ProfilEditionType;
use AppBundle\Images\ImageManipulator;
use AppBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")

 */
class UserController extends Controller
{
    /**
     * @Route("/", name="member_area")
     */
    public function connectedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findBy(['user' => $this->getUser()]);
        $files = $em->getRepository(Files::class)->findOneBy(['user' => $this->getUser()]);

        return $this->render('user/index.html.twig', array(
            'articles' => $articles,
            'files' => $files
        ));
    }

    /**
     * @Route("/first-connexion", name="first_connexion")
     */
    public function firstConnectionAction(Request $request, ImageManipulator $imageManipulator)
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilEditionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = new Files();
            $files->setUser($this->getUser());

            if ($form['pictureProfil']->getdata()) {
                $pictureProfil = $user->getPictureProfil();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $imageManipulator->handleUploadedPicture($pictureProfil, $fileNamePicture);

                $user->setPictureProfil( $this->getParameter('upload_Path_Profil_Picture') . $fileNamePicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($files);
            $em->flush();

            return $this->redirectToRoute('member_area');
        }

        return $this->render('user/firstConnexion.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/edit-profil", name="edit_profil")
     */
    public function editProfilAction(Request $request, ImageManipulator $imageManipulator)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$em->getRepository(Files::class)->findOneBy(['user' => $user])) {
            $files = new Files();
            $files->setUser($user);
            $em->persist($files);
        }

        $currentPicture = $this->getUser()->getPictureProfil();

        $editform = $this->createForm(ProfilEditionType::class, $user);
        $editform->handleRequest($request);

        if ($editform->isSubmitted() && $editform->isValid()) {

            if ($editform['pictureProfil']->getdata()) {
                $pictureProfil = $user->getPictureProfil();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $imageManipulator->handleUploadedPicture($pictureProfil, $fileNamePicture);

                $user->setPictureProfil( $this->getParameter('upload_Path_Profil_Picture') . $fileNamePicture);

                if ($currentPicture) {
                    unlink($currentPicture);
                }
            }

            if (!$editform['pictureProfil']->getdata()) {
                $user->setPictureProfil($currentPicture);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('member_area');
        }

        return $this->render('user/editProfil.html.twig', array('editForm' => $editform->createView()));
    }


    /**
     * @Route("/{role}/delete-user/{id}", defaults={"role": "admin"},
     *     name="delete_user")
     */
    public function deleteUserAction($id, $role,UserManager $userManager)
    {
        if (!$userManager->findUser($id)->getDeleteAt()) {

            $userManager->deleteUser($id);

            if ($role == 'admin') {

                return $this->redirectToRoute('admin_users');
            }

            return $this->redirectToRoute('logout');
        }

        elseif ($userManager->findUser($id)->getDeleteAt()) {

            $userManager->keepUser($id);

            if ($role == 'admin') {

                return $this->redirectToRoute('admin_users_inactives');
            }

            return $this->redirectToRoute('logout');
        }
    }

    /**
     * @Route("/{role}/disable-user/{id}", defaults={"role": "admin"},
     *     name="disable_user")
     *
     */
    public function disableUserAction($id, $role, UserManager $userManager)
    {
        if (!$userManager->findUser($id)->getDisableAt()) {

            $userManager->disableUser($id);

            if ($role === 'admin') {

                return $this->redirectToRoute('admin_users');
            }

            return $this->redirectToRoute('logout');
        }

        elseif ($userManager->findUser($id)->getDisableAt()) {

            $userManager->reactiveUser($id);

            if ($role === 'admin') {

                return $this->redirectToRoute('admin_users_inactives');
            }

            return $this->redirectToRoute('logout');

        }
    }

    /**
     * @Route("/new")
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

        return $this->render('user\new.html.twig', ['form' => $form->createView()]);
    }
}
