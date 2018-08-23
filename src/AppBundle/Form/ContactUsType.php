<?php

namespace AppBundle\Form;

use AppBundle\Entity\ContactUs;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('lastName', TextType::class, array(
                'label' => 'Nom',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('subject', ChoiceType::class, array(
                'label' => 'Objet',
                'constraints' => new NotNull(['message' => 'Ce champs ne doit pas être nul']),
                'choices' => [
                    'Membre de l\'association : demander vos accès' => ContactUs::CONTACT_MEMBERS_ACCES,
                    'Visiteur : demande d\'informations' => ContactUs::CONTACT_VISITORS
                ]))
            ->add('register', TextType::class, array(
                'label' => 'Matricule',
                'required' => false))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('email', EmailType::class, array(
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ContactUs::class,
        ));
    }
}
