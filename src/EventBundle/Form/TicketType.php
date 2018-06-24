<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventId','hidden')
            ->add('name','text',array('required'=>false))
            ->add('ticketCategory', 'entity', array(
                'class' => 'EventBundle:TicketCategory',
                'property' => 'name',
                'empty_value' =>'Select Type',
                'required'=>false))
            ->add('quantity','text',array('required'=>false))
            ->add('maximumTicketBuy','text',array('required'=>false))
            ->add('price','text',array('required'=>false))
            ->add('tax','text',array('required'=>false))
            ->add('addTicket','button',array('label'=> 'Add Ticket'))
            ->add('save','button',array('label'=> 'Continue'));
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_ticket';
    }
}
