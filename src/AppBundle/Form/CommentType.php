<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentText', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('commentMark', ChoiceType::class, array(
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    '1.0' => 1,
                    '2.0' => 2,
                    '3.0' => 3,
                    '4.0' => 4,
                    '5.0' => 5,
                ),
                // *this line is important*
                'choices_as_values' => true,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment',
        ));
    }
}
