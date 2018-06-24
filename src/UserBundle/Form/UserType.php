<?php

namespace UserBundle\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
    private $userSection;
    private $editForm;

    public function __construct($userSection= true, $editForm = true)
    {
        $this->userSection = $userSection;
        $this->editForm = $editForm;

    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname','text',array(
                'label' => 'First Name:',
                'pattern' => '^([a-zA-Z]+[a-zA-Z0-9_-\s]{2,15})$',
                'required' => true,'constraints' => array(
                    new NotBlank(array(
                        'message'=>'First Name should not be blank'

                    )))))
            ->add('lastname','text',array('label' => 'Last Name:',
                  'pattern' => '^([a-zA-Z]+[a-zA-Z0-9_-\s]{2,15})$',
                  'constraints' => array(
                new NotBlank(array(
                    'message'=>'Last Name should not be blank'

                )))))
            ->add('cellphone','text',array(
                'pattern' => '\+?\(?\d{2,4}\)?[\d\s-]{3,}$',
                'label' => 'Phone',
                'required' => false))
            ->add('gender','choice', array(
                'mapped' => true,
                'choices' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                ),
                'required'  => true,
                'empty_value' => 'Select Gender',
                'empty_data' => ''
            ));
            if($this->userSection)
            {
                    $builder->add('single_role', 'choice', array(
                     'mapped' => true,
                    'choices' => array(
                        'ROLE_ADMIN' => 'Admin',
                        'ROLE_USER' => 'User',
                    ),
                    'required'  => true,
                    'empty_value' => 'Select User Group',
                    'empty_data' => ''
                ));
            }

           $builder
            ->add('email', 'email', array('label' => 'Email',
                   'pattern' => '.+\@.+\..+$',
                   'translation_domain' => 'FOSUserBundle', 'constraints' => array(

                new NotBlank(array(
                    'message'=>'Email should not be blank'
                )),
                new Email(array('message' => 'Invalid email address.')))) )
           ;
            if($this->editForm == false)
               {
                   $builder

                       ->add('plainPassword', 'repeated', array(
                   'type' => 'password',
                   'options' => array('translation_domain' => 'FOSUserBundle'),
                   'first_options' => array('label' => 'form.password'),
                   'second_options' => array('label' => 'form.password_confirmation'),
                   'invalid_message' => 'fos_user.password.mismatch',
               ));
               }
       $builder
            ->add('save','submit',array('label'=> 'Save'))
            ->add('cancel','button')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_user';
    }
}
