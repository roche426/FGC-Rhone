<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/blog")
 */
class BlogController extends Controller
{

    /**
     * @Route("/", name="blog_home")
     */
    public function indexBlogAction()
    {
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository(Blog::class)->findBy(['user'=> $this->getUser()]);
        return $this->render('blog/index.html.twig', ['blogs' => $blogs]);
    }

    /**
    * @Route("/edit/{id}", name="blog_edit")
     */
    public function editBlogAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->find($id);

        $editform = $this->createForm('AppBundle\Form\BlogType', $blog);
        $editform->handleRequest($request);

        if ($editform->isSubmitted() && $editform->isValid()) {

            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog_home');
        }

        return $this->render('blog/edit.html.twig',
            ['edit_form' => $editform->createView()]);
    }

    /**
    * @Route("/new", name="blog_new")
     */
    public function newBlogAction(Request $request)
    {
        $blog = new Blog();
        $blog->setPublicationDate(new \DateTime('now'));

        $form = $this->createForm('AppBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog_home');
        }

        return $this->render('blog/new.html.twig',
            ['form' => $form->createView()]);
    }

    /**
    * @Route("/delete{id}", name="blog_delete")
     */
    public function deleteBlogAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->find($id);

        $em->remove($blog);
        $em->flush();

        return $this->redirectToRoute('blog_home');
    }

}
