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
     * @Route("/contact", name="contact")
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
}
