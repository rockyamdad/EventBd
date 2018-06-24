<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SponsorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('event','hidden')
            ->add('description', 'textarea', array('required' => false))
            ->add('file','file', array('label' => 'Logo'))
            ->add('addSponsor','button',array('label'=> 'Add Sponsor'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Sponsor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_sponsor';
    }
}
