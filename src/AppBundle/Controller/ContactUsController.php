<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Form\ContactUsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    public function showOneMessageAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository(ContactUs::class)->find($id);

        return $this->render('admin/showOneMessage.html.twig', array('message' => $message));
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
     * @Route(name="response_contact")
     */
    public function ResponseContactAction(Request $request)
    {
        $from = $request->get('email');
        $to = $request->get('emailTo');
        $subject = $request->get('subject');
        $message = $request->get('message');

        //ajouter envoi mail + flash message

        return $this->redirectToRoute('admin_show_messages');
    }

}
