<?php

namespace AppBundle\Form;

use AppBundle\Entity\ImageFolders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageFoldersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom du dossier',
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('description', TextType::class, array(
                'label' => 'Description',
                'required' => false))
            ->add('image', FileType::class, array(
                'label' => 'Image de couverture',
                'required' => false,
                'constraints' => array(
                    new Image(['mimeTypesMessage' => 'Format de l\'image invalide']),
                    new NotBlank(['message' => 'Ce champs ne doit pas être vide'])),
                'data_class' => null));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ImageFolders::class,
        ));
    }
}
