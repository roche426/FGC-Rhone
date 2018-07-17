<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     */
    public function connectedAction()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function showUsersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findAll();

        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/{id}", name="admin_see_user")
     */
    public function showOneUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $articles = $em->getRepository(Blog::class)->findAll($id);

        return $this->render('admin/showUser.html.twig', [
            'user' => $user,
            'articles' => $articles]);
    }


}
