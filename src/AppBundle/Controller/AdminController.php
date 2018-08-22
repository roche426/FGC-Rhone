<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Comment;
use AppBundle\Entity\ContactUs;
use AppBundle\Entity\Files;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
        $messagesContactUs = $this->getDoctrine()->getManager()->getRepository(ContactUs::class)->findLastMessageNoTreated();

        return $this->render('admin/index.html.twig', ['messagesContactUs' => $messagesContactUs]);
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function showUsersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findActivesUsers();

        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/inactives", name="admin_users_inactives")
     */
    public function showInactivesUsersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findInactivesUsers();

        return $this->render('admin/InactivesUsers.html.twig', ['users' => $users]);
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
        $articles = $em->getRepository(Blog::class)->findby(['user' => $id]);
        $files = $em->getRepository(Files::class)->findOneBy(['user' => $id]);

        return $this->render('admin/showUser.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'files' => $files]);
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

    /**
     * @Route("/messages", name="admin_show_messages")
     */
    public function showMessagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $messagesContactUs = $em->getRepository(ContactUs::class)->findAll();

        return $this->render('admin/showMessages.html.twig', [
            'messagesContactUs' => $messagesContactUs,
        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="delete_comment")
     */
    public function deleteCommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($id);

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('admin_articles');
    }

    /**
     * @Route("/article/delete/{id}", name="delete_article")
     */
    public function deleteArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Blog::class)->find($id);

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('admin_articles');
    }


}
