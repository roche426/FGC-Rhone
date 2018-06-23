<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('front/index.html.twig');
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
     * @Route("/actuality", name="actuality")
     */
    public function actualityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Blog::class)->findAll();

        return $this->render('front/actuality.html.twig', array('articles' => $articles));
    }

}
