<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilEditionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthDate', DateType::class)
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('address', TextType::class)
            ->add('additionalAddress', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class)
            ->add('pictureProfil', FileType::class, array(
                'required' => false,
                'data_class' => null
            ));


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
