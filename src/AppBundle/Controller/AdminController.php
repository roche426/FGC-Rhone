<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Comment;
use AppBundle\Entity\ContactUs;
use AppBundle\Entity\Files;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     */
    public function indexAction()
    {
        $messagesContactUs = $this->getDoctrine()->getManager()->getRepository(ContactUs::class)->findLastMessageNoTreated();

        return $this->render('admin/index.html.twig', ['messagesContactUs' => $messagesContactUs]);
    }


    /* === SECTION SUR LES MEMBRES DU SITE ===*/
    /**
     * @Route("/users", name="admin_users")
     */
    public function listUsersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findActivesUsers();

        return $this->render('admin/listUsers.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/inactives", name="admin_users_inactives")
     */
    public function listInactivesUsersAction()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findInactivesUsers();

        return $this->render('admin/listInactivesUsers.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/{id}", name="admin_show_user")
     */
    public function showUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $articles = $em->getRepository(Blog::class)->findby(['user' => $id]);
        $files = $em->getRepository(Files::class)->findBy(['user' => $id]);

        $editForm = $this->createFormBuilder($user)
            ->add('isAdmin', ChoiceType::class, array(
                'constraints' => new NotNull(['message' => 'Ce champs ne doit pas être nul']),
                'choices' => [
                    'Administrateur' =>  true,
                    'Utilisateur' =>  false]))
            ->add('statut', ChoiceType::class, array(
                'choices' => [
                    'Membre' =>  null,
                    'Président' =>  User::CHAIRMAN,
                    'Trésorier' =>  User::TREASURER,
                    'Sécrétaire' =>  User::SECRETARY]))
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($user);
            $em->flush();
        }

        return $this->render('admin/showUser.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'files' => $files,
            'editForm' => $editForm->createView()]);
    }


    /* === SECTION SUR LES ARTICLES ===*/
    /**
     * @Route("/articles", name="admin_articles")
     */
    public function listArticlesAction()
    {
        $em = $this->getDoctrine()->getRepository(Blog::class);
        $articles = $em->findAll();

        return $this->render('admin/listArticles.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/{id}", name="admin_show_article")
     */
    public function showArticleAction($id)
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

    /* === SECTION SUR LES MESSAGES DE CONTACT ===*/

    /**
     * @Route("/messages", name="admin_show_messages")
     */
    public function listMessagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $messagesContactUs = $em->getRepository(ContactUs::class)->findAll();

        return $this->render('admin/listMessages.html.twig', [
            'messagesContactUs' => $messagesContactUs,
        ]);
    }

    /**
     * @Route("/admin/messages/{id}", name="show_one_message")
     */
    public function showMessageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(ContactUs::class)->find($id);

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, array(
                'data' => $this->getUser()->getEmail(),
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('emailTo', EmailType::class, array(
                'data' => $message->getEmail(),
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('subject', TextType::class, array(
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('message', TextareaType::class, array(
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();

            $from = $data['form']['email'];
            $to = $data['form']['emailTo'];
            $subject = $data['form']['subject'];
            $responseContact = $data['form']['message'];

            $message->setResponse($responseContact);
            $em->persist($message);
            $em->flush();
            //ajouter envoi mail + flash message

            return $this->redirectToRoute('message_treated', ['id' => $id]);
        }


        return $this->render('admin/showMessage.html.twig', array(
            'message' => $message,
            'form' => $form->createView()));
    }

    /**
     * @Route("/messages/delete/{id}", name="delete_message")
     */
    public function deleteMessageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(ContactUs::class)->find($id);

        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/messages/treated/{id}", name="message_treated")
     */
    public function treatedMessageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(ContactUs::class)->find($id);
        $message->setIsTreated(new \DateTime('now'));

        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('admin_show_messages');

    }


}
