<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Mail\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setToken(uniqid('FGPR'));

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->accountActivationEmail($user);

            $userEmail = $user->getEmail();
            $flashType = 'success';
            $flashMessage = 'Vous êtes enregistré ! Afin de finaliser votre inscription, un email de confirmation vous a été transmis à l\'adresse suivante : ' . $userEmail;
            $this->addFlash($flashType, $flashMessage);

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/confirmation_mail/{slug}", name="mailConfirmation", defaults={"slug" = null})
     */
    public function mailConfirmation(Request $request)
    {
        $tokenUrl = $request->get('slug');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(
            ['token' => $tokenUrl]
        );

        if($user) {
            $user->setToken(null);

            $user->setIsActive(true);

            $em->persist($user);
            $em->flush();

            $flashType = 'success';
            $flashMessage = 'Votre compte est activé !';

        } else {
            $flashType = 'danger';
            $flashMessage = 'Impossible d\'activer le compte.';
        }

        $this->addFlash($flashType, $flashMessage);

        return ($this->redirectToRoute('login'));
    }



    /**
     * Password forgotten
     *
     * @Route("/password/new", name="forgotPassword")
     * @Method({"GET", "POST"})
     */
    public function forgotPasswordAction(Request $request, Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\ForgotPasswordType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$user` variable has also been updated
            $userForm = $form->getData();

            $emailForm = $userForm->getEmail();

            //$user = new user();
            $em = $this->getDoctrine()->getManager();

            //user in db with this email
            $user = $em->getRepository(User::class)->findOneBy(
                ['email' => $emailForm]
            );

            if ($user) {
                //Unique Id Token fill in
                $user->setToken(uniqid('mf'));

                //Token persisted in db
                $em->persist($user);
                $em->flush();

                // Send MAIL to emailForm
                $mailer->forgotPasswordEmail($user);

                //message flash parameters
                $flashType = 'success';
                $flashMessage = 'Un email vous a été transmis à l\'adresse suivante : ' . $emailForm;

            } else {
                //message flash parameters
                $flashType = 'danger';
                $flashMessage = 'Cet email ne correspond à aucun utilisateur !';
            }

            //message flash
            $this->addFlash($flashType, $flashMessage);

            //Redirection
            return ($this->redirectToRoute('login'));
        }

        return $this->render('security/forgotPassword.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update Password forgotten
     *
     * @Route("/password/update/{slug}", name="updatePassword", defaults={"slug" = null})
     * @Method({"GET", "POST"})
     */
    public function updatePasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UpdatePasswordType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //form fill in
            $userForm = $form->getData();
            $plainPassword = $userForm->getPassword();

            //URL information
            $tokenUrl= $request->get('slug');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(
                ['token' => $tokenUrl]
            );

            if($user) {
                //user Update
                $encoded = $encoder->encodePassword($user, $plainPassword); //password encoded
                $user->setPassword($encoded); //user Password encoded update

                $user->setToken(null); //user token update

                //user updates persisted in db
                $em->persist($user);
                $em->flush();

                //message flash parameters
                $flashType = 'success';
                $flashMessage = 'Votre mot de passe est modifié !';

            } else {
                //message flash parameters
                $flashType = 'danger';
                $flashMessage = 'Le mot de passe ne peut être modifié !';
            }

            //message flash
            $this->addFlash($flashType, $flashMessage);

            //Redirection
            return ($this->redirectToRoute('login'));

        }

        return $this->render('security/updatePassword.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}
