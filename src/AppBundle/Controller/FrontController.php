<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/home", name="homePage")
     */
    public function homePageAction()
    {
        $em = $this->getDoctrine();
        $blogs = $em->getRepository(Blog::class)->findTwoLastArticles();
        return $this->render('front/homePage.html.twig', ['blogs' => $blogs]);
    }

    /**
     * @param $id
     * @Route("/blog/{id}"), name="showArticle"
     * @Method({"POST", "GET"})
     * @return string
     */
    public function displayBlogAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Blog::class)->findOneBy(array('id' => $id));

        return $this->render('front/blog.html.twig', array(
            'article' => $article,
        ));
    }

    /**
     * @Route("/blog", name="actuality")
     */
    public function actualityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findAll();

        return $this->render('front/actuality.html.twig', array('articles' => $articles));
    }

}
