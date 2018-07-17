<?php

namespace AppBundle\Form;

use AppBundle\Entity\SharedFiles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SharedFilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pathFile', FileType::class, [
                'data_class' => null
            ])
            ->add('nameFile', TextType::class)
            ->add('fileAccess', ChoiceType::class, array(
                'choices' => [
                    'Tout le monde' => SharedFiles::PUBLIC_ACCESS_FILE,
                    'Membres' => SharedFiles::MEMBERS_ACCESS_FILE,
                    'Membres du bureau' => SharedFiles::BUREAU_MEMBERS_ACCESS_FILE,
                    'Administrateur' => SharedFiles::ADMIN_ACCESS_FILE
                ]
            ))
            ->add('isShared', ChoiceType::class, array(
                'choices' => [
                    'Partager plus tard' => false,
                    'Partager maintenant' => true
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SharedFiles::class,
        ));
    }
}
