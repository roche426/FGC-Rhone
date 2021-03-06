<?php

namespace AppBundle\Form;

use AppBundle\Entity\Files;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => false,
                'attr' => ['placeholder' => 'Indiquer le nom du fichier'],
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('path', FileType::class, array(
                'label' => false,
                'constraints' => array(
                    new File(['mimeTypesMessage' => 'Format du fichier invalide']),
                    new NotBlank(['message' => 'Ce champs ne doit pas être vide'])),
                'data_class' => null));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Files::class,
        ));
    }
}
