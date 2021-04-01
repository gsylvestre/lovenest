<?php

namespace App\Form;

use App\Entity\ProfilePicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pic', FileType::class, [
                'mapped' => false,
                'label' => 'Choisissez un fichier !',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un fichier !'
                    ]),
                    new Image([
                        'maxSize' => '8M',
                        'maxSizeMessage' => '20 mégas max svp !',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfilePicture::class,
            'nouvelle_option' => []
        ]);
    }
}
