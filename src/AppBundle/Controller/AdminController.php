<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Comment;
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
     * @Route("/articles", name="admin_articles")
     */
    public function showArticlesAction()
    {
        $em = $this->getDoctrine()->getRepository(Blog::class);
        $articles = $em->findAll();

        return $this->render('admin/articles.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/users/{id}", name="admin_show_user")
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


    /**
     * @Route("/article/{id}", name="admin_show_article")
     */
    public function showOneArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Blog::class)->find($id);
        $comments = $em->getRepository(Comment::class)->findAll(['user' => $id]);

        return $this->render('admin/showArticle.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);
    }


}
