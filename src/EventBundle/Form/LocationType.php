<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventId','hidden')
            ->add('venueName','text')
            ->add('address','text')
            ->add('city','text',array('required'=>false))
            ->add('state','text',array('required'=>false))
            ->add('postalCode','text')
            ->add('latitude','hidden',array('required'=>false))
            ->add('longitude','hidden',array('required'=>false))
            ->add('country', 'entity', array(
                'class' => 'EventBundle:Country',
                'property' => 'name',
                'empty_value' =>'Select Country',
                'required'=>false))
            ->add('save','submit',array('label'=> 'Continue'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\location'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_location';
    }
}
