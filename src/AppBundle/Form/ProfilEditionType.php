<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilEditionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthDate', BirthdayType::class, array(
                'label' => 'Date de naissance',
                'format' => 'dd-MMM-yyyy',
                'choice_translation_domain' => true))
            ->add('city', TextType::class, array(
                'label' => 'Ville',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('postalCode', TextType::class, array(
                'label' => 'Code postal',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('address', TextType::class, array(
                'label' => 'adresse',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('additionalAddress', TextType::class, array(
                'label' => 'Complément d\'adresse',
                'required' => false))
            ->add('phoneNumber', TelType::class, array(
                'label' => 'Numéro de téléphone',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('pictureProfil', FileType::class, array(
                'label' => 'Télécharger votre photo',
                'required' => false,
                'constraints' => new Image(['mimeTypesMessage' => 'Format de l\'image invalide']),
                'data_class' => null))
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('lastName', TextType::class, array(
                'label' => 'Nom',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('email', EmailType::class, array(
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'with_upload_file' => true,
        ));
    }
}
