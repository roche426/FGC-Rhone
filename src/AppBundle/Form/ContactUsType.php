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

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('label' => 'Prénom'))
            ->add('lastName', TextType::class, array('label' => 'Nom'))
            ->add('subject', ChoiceType::class, array(
                'label' => 'Objet',
                'choices' => [
                    'Membre de l\'association : demander vos accès' => ContactUs::CONTACT_MEMBERS_ACCES,
                    'Visiteur : demande d\'informations' => ContactUs::CONTACT_VISITORS
                ]))
            ->add('register', TextType::class, array('label' => 'Matricule'))
            ->add('message', TextareaType::class, array('label' => 'Message'))
            ->add('email', EmailType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ContactUs::class,
        ));
    }
}
