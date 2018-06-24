<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'textarea', array(
                'required'=>true,
                'attr' => array(
                    'placeholder' => 'Description'
                )
            ))
            ->add('title', 'text', array(
                'required'=>true,
                'attr' => array(
                    'placeholder' => 'Title'
                )
            ))
            ->add('file', 'file', array(
            'required'=>true,
            'attr'=>array(
                'aria-describedby'=>'document_file-error'
            )))
            ->add('save', 'submit')
            ->add('cancel', 'button')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Document'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document';
    }
}
