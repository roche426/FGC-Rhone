<?php

namespace AppBundle\Form;

use AppBundle\Entity\SharedFiles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SharedFilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pathFile', FileType::class, array(
                'label' => 'Téléchargement',
                'constraints' => new File(['mimeTypesMessage' => 'Format de l\'image invalide']),
                'data_class' => null))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('description', TextType::class,  array(
                'label' => 'Description',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('nameFile', TextType::class,  array(
                'label' => 'Nom du fichier',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('fileAccess', ChoiceType::class, array(
                'choices' => [
                    'Tout le monde' => SharedFiles::PUBLIC_ACCESS_FILE,
                    'Membres' => SharedFiles::MEMBERS_ACCESS_FILE,
                    'Membres du bureau' => SharedFiles::BUREAU_MEMBERS_ACCESS_FILE,
                    'Administrateur' => SharedFiles::ADMIN_ACCESS_FILE
                ],
                'label' => 'Droits d\'accès',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('isShared', ChoiceType::class, array(
                'choices' => [
                    'Partager plus tard' => false,
                    'Partager maintenant' => true
                ],
                'label' => 'Partage du fichier',
                'constraints' => new NotNull(['message' => 'Ce champs ne doit pas être nul'])));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SharedFiles::class,
        ));
    }
}
