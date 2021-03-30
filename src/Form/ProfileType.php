<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthday', BirthdayType::class, ['label' => 'Date de naissance'])
            ->add('gender', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'label' => 'Sexe',
                'choices' => [
                    'Femme' => "f",
                    'Homme' => "m",
                    'Autre' => "o",
                ]
            ])
            ->add('postalCode', null, ['label' => 'Code postal'])
            ->add('city', null, ['label' => 'Ville', 'required' => false])
            ->add('description', null, ['label' => 'Décrivez-vous !', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
