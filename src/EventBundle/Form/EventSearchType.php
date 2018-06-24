<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventSearchType extends AbstractType
{

    private $page;
    public function __construct($page)
    {
        $this->page = $page;

    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('name', 'text',array('required'=> false))
                ->add('location', 'text',array('required'=> true))
                ->add('start_date', 'datetime', array(
                    'widget' => 'single_text',
                    'required'=> false
                ))
                ->add('end_date', 'datetime', array(
                    'widget' => 'single_text',
                    'required'=> false
                ));
                if($this->page == 'searchPageSearch'){
                    $builder->add('group', 'entity', array(
                    'required'    => false,
                    'class'       => 'EventBundle:EventGroup',
                    'property'    => 'name',
                    'empty_value' => 'Select Event Type'));
                }


    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search';
    }
}
