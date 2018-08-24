<?php

namespace AppBundle\Form;

use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Titre'),
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])))
            ->add('article', FroalaEditorType::class, array(
                'attr' => array(
                    'cols' => 50),
                'constraints' => new NotBlank(['message' => 'Ce champs ne doit pas être vide'])));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Blog',
            'with_upload_file' => true,
        ));
    }
}
