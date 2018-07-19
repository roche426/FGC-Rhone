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

        $currentPicture = $blog->getImageArticle();

        $editform = $this->createForm('AppBundle\Form\BlogType', $blog);
        $editform->handleRequest($request);

        if ($editform->isSubmitted() && $editform->isValid()) {

            if ($editform['imageArticle']->getdata()) {
                $pictureProfil = $blog->getImageArticle();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $pictureProfil->move($this->getParameter('upload_Path_Article_Picture'), $fileNamePicture);

                $blog->setImageArticle($this->getParameter('upload_Path_Article_Picture') . $fileNamePicture);

                if ($currentPicture) {
                    unlink($currentPicture);
                }
            }

            if (!$editform['imageArticle']->getdata()) {
                $blog->setImageArticle($currentPicture);
            }

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

        $form = $this->createForm('AppBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUser($this->getUser());

            if ($form['imageArticle']->getdata()) {
                $pictureProfil = $blog->getImageArticle();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $pictureProfil->move($this->getParameter('upload_Path_Article_Picture'), $fileNamePicture);
                $blog->setImageArticle( $this->getParameter('upload_Path_Article_Picture') . $fileNamePicture);
            }

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

        $currentPicture = $blog->getImageArticle();

        if ($currentPicture) {
            unlink($currentPicture);
        }

        $em->remove($blog);
        $em->flush();

        return $this->redirectToRoute('blog_home');
    }

}
