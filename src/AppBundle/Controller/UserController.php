<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Files;
use AppBundle\Entity\User;
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
        return $this->render('user/index.html.twig');
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
        $user = $this->getUser();
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

            $em = $this->getDoctrine()->getManager();
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
    public function deleteUserAdminAction($id, $role,UserManager $userManager)
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


}
