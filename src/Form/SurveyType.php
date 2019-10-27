<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Название опроса'))
            ->add('status', HiddenType::class, array(
                'data' => 'draft',
                'label' => false))
            ->add('save', SubmitType::class, array('label' => 'Сохранить опрос'))
        ;
        $builder->add('questions', CollectionType::class, array(
            'label' => false,
            'entry_type' => QuestionType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_name' => '__question_prot__',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
