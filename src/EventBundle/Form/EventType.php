<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    private $settingSection;
    public function __construct($settingSection)
    {
        $this->settingSection = $settingSection;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($this->settingSection == false) {
            $builder
                ->add('id','hidden')
                ->add('name', 'text', array('required' => true))
                ->add('facebookPageLink', 'textarea', array('required' => false))
                ->add('googlePlusPageLink', 'textarea', array('required' => false))
                ->add('linkedinLink', 'textarea', array('required' => false))
                ->add('tags', 'text', array('required' => false))
                ->add('description', 'textarea', array('required' => false))
                ->add('contact', 'textarea', array('required' => true))
                ->add('group', 'entity', array(
                    'class'       => 'EventBundle:EventGroup',
                    'property'    => 'name',
                    'empty_value' => 'Select Event Type'))
                ->add('file', 'file', array('label' => 'Logo', 'required' => false))
                ->add('save', 'submit', array('label' => 'Continue'))
                ->add('privacy', 'choice', array(
                    'mapped'      => true,
                    'choices'     => array(
                        'Private' => 'Private',
                        'Public'  => 'Public',
                    ),
                    'required'    => false,
                    'empty_value' => 'Select Privacy',
                    'empty_data'  => ''
                ));
        }
              if($this->settingSection) {
              $builder
                 /* ->add('hasAdvancedFeature', 'choice', array(
                      'label' => 'Event Has Advanced Feature?',
                      'choices'   => array('1' => 'Yes', '0' => 'No'),
                      'expanded' => true,
                      'required'  => true,
                  ))*/
                      ->add('hasNews','checkbox',array('required'=>false))
                      ->add('hasSpeaker','checkbox',array('required'=>false))
                      ->add('hasSponsor','checkbox',array('required'=>false))
                      ->add('save', 'submit', array('label' => 'Save & Exit'));
                  }

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_event';
    }
}
