<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\SearchCriterias;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCriteriasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minAge', IntegerType::class, ['label' => 'Âge minimal'])
            ->add('maxAge', IntegerType::class, ['label' => 'Âge maximal'])
            ->add('genders', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'Sexes',
                'choices' => [
                    'Femme' => "f",
                    'Homme' => "m",
                    'Autre' => "o",
                ]
            ])
            ->add('departments', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchCriterias::class,
        ]);
    }
}
