<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("home", name="home_page")
     */
    public function homePageAction()
    {
        $em = $this->getDoctrine();
        $blogs = $em->getRepository(Blog::class)->findTwoLastArticles();
        return $this->render('front/homePage.html.twig', ['blogs' => $blogs]);
    }

    /**
     * @Route("blog/{id}", name="show_article")
     */
    public function displayBlogAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findOneBy(array('id' => $id));
        $comments = $em->getRepository(Comment::class)->findAll(array('id' => $id));

        $comment = new Comment();
        $form = $this->createForm('AppBundle\Form\CommentType', $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setPublicationDate(new \DateTime('now'));
            $comment->setUser($this->getUser());
            $comment->setArticle($articles);

            $em->persist($comment);
            $em->flush();

            return $this->redirect($id);
        }

        return $this->render('front/blog.html.twig', array(
            'article' => $articles,
            'comments' => $comments,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("blog", name="actuality")
     */
    public function actualityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findAll();

        return $this->render('front/actuality.html.twig', array('articles' => $articles));
    }

    /**
     * @Route("bureau-members", name="bureau_members")
     */
    public function bureauMembersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $members = $em->findAll();

        return $this->render('front/bureauMembers.html.twig', array('members' => $members));
    }



}
