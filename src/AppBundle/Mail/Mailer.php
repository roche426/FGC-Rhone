<?php
namespace AppBundle\Mail;

use AppBundle\Entity\User;

class Mailer
{
    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $mail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->mail = $mail;
    }

    protected function sendMail($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($this->mail)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo($this->mail)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }

    public function forgotPasswordEmail(User $user)
    {
        $subject = "Réinitialisation de votre mot de passe";
        $to = $user->getEmail();
        $body = $this->templating->render('mail/forgotPassword.html.twig', array('user' => $user));
        $this->sendMail($to, $subject, $body);
    }

    public function registrationActivationEmail(User $user)
    {
        $subject = "Confirmation d'inscription";
        $to = $user->getEmail();
        $body = $this->templating->render('mail/registrationActivation.html.twig', array('user' => $user));
        $this->sendMail($to, $subject, $body);
    }

    public function contactUsResponseMail($to, $subject, $responseMessage, $initialMessage)
    {
        $body = $this->templating->render('mail/contactUsResponse.html.twig', array(
            'message' => $responseMessage,
            'inialMessage' => $initialMessage));
        $this->sendMail($to, $subject, $body);
    }


    public function deleteAccountUser(User $user, $message)
    {
        $subject = "Vous nous quittez?";
        $to = $user->getEmail();
        $body = $this->templating->render('mail/deleteAccountUser.html.twig', array(
            'user' => $user,
            'message' => $message));
        $this->sendMail($to, $subject, $body);
    }

}
