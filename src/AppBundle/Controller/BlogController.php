<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Images\ImageManipulator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    public function editBlogAction(Request $request, ImageManipulator $imageManipulator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->find($id);

        $currentPicure = $blog->getImageArticle();

        $editform = $this->createForm('AppBundle\Form\BlogType', $blog);
        $editform->handleRequest($request);

        if ($editform->isSubmitted() && $editform->isValid()) {

            if ($editform['imageArticle']->getdata()) {
                $pictureProfil = $blog->getImageArticle();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $imageManipulator->handleUploadedArticlePicture($pictureProfil, $fileNamePicture);

                $blog->setImageArticle($this->getParameter('upload_Path_Article_Picture') . $fileNamePicture);

                if ($currentPicure) {
                    unlink($currentPicure);
                }
            }

            if (!$editform['imageArticle']->getdata()) {
                $blog->setImageArticle($currentPicure);
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
    public function newBlogAction(Request $request, ImageManipulator $imageManipulator)
    {
        $blog = new Blog();

        $form = $this->createForm('AppBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUser($this->getUser());

            if ($form['imageArticle']->getdata()) {
                $pictureProfil = $blog->getImageArticle();

                $fileNamePicture = uniqid() . '.' . $pictureProfil->guessExtension();

                $imageManipulator->handleUploadedArticlePicture($pictureProfil, $fileNamePicture);

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
