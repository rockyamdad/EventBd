<?php

namespace VendorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Form\UserType;

class VendorProfileType extends AbstractType
{
    private $vendorProfileEdit;


    public function __construct($vendorProfileEdit= true)
    {
        $this->vendorProfileEdit = $vendorProfileEdit;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->vendorProfileEdit)
        {
            $builder
                ->add('user', new UserType($newEntry = false, $editForm = false),array(
                        'label_attr'=>array(
                            'class'=> 'hidden'
                        )
                    )
                );
        }
        else{
            $builder
                ->add('user', new UserType($newEntry = false, $editForm = true),array(
                        'label_attr'=>array(
                            'class'=> 'hidden'
                        )
                    )
                );
        }
        $builder
            ->add('companyName','text', array('required'=> false))
            ->add('phone','text',array('required'=> false))
            ->add('address','textarea', array('required'=> false))
            ->add('description','textarea', array('required'=> false))
            ->add('file','file', array('label' => 'Logo'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VendorBundle\Entity\VendorProfile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vendorbundle_vendorprofile';
    }
}
