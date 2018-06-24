<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpeakerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event','hidden')
            ->add('firstName')
            ->add('lastName')
            ->add('description')
            ->add('file','file', array('label' => 'Logo','required'=>false))
            ->add('addSpeaker','button',array('label'=> 'Add Speaker'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Speaker'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_speaker';
    }
}
