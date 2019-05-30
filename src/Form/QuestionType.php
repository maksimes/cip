<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', null, array('label' => 'Текст вопроса'))
            ->add('type', ChoiceType::class, array(
                'choices' => array('Множественный выбор' => 'checkbox', 'Один правильный ответ' => 'radio'),
                'label' => 'Тип ответов'
            ))
            ->add('required', null, array('label' => 'Сделать вопрос обязательным для ответа'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
