<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\ContactUsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ContactUsController extends Controller
{
    /**
     * @Route("contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new ContactUs();

        $contact->setSubject($request->query->get('subject'));
        $form = $this->createForm(ContactUsType::class, $contact);
        $form->handleRequest($request);

        $contact->setDate(new \DateTime('now'));

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('home_page');
        }

        return $this->render('front/contact.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/messages/{id}", name="show_one_message")
     */
    public function showOneMessageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(ContactUs::class)->find($id);

        $form = $this->createFormBuilder()
            ->add('email', TextType::class, array(
                'data' => $this->getUser()->getEmail(),
                'required' => true))
            ->add('emailTo', EmailType::class, array(
                'data' => $message->getEmail(),
                'required' => true))
            ->add('subject', TextType::class, array(
                'required' => true))
            ->add('message', TextareaType::class, array(
                'required' => true))
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


        return $this->render('admin/showOneMessage.html.twig', array(
            'message' => $message,
            'form' => $form->createView()));
    }


    /**
     * @Route("/admin/messages/delete/{id}", name="delete_message")
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
     * @Route("/admin/messages/treated/{id}", name="message_treated")
     */
    public function treatedMessageAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(ContactUs::class)->find($id);
        $message->setIsTreated(new \DateTime('now'));

        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('admin_show_messages');

    }

}
