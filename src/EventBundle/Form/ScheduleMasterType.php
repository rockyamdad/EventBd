<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScheduleMasterType extends AbstractType
{
    private $multipleSchedule;

    /** @var  Request */
    private $request;
    public function __construct($multipleSchedule, $request)
    {
        $this->multipleSchedule = $multipleSchedule;
        $this->request = $request;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $frequency = $this->request->request->get('eventbundle_schedule_master[frequency]', null, true);
            $month = ['1st','2nd','3rd','4th','5th','6th','7th','8th','9th','10th',
                      '11th','12th','13th','14th','15th','16th','17th','18th','19th','20th',
                      '21st','22nd','23rd','24th','25th','26th','27th','28th','29th','30th','31st'];

            $month_combine = array_combine($month, $month);
            $builder
                      ->add('startDate', 'date', array(
                          'widget' => 'single_text',
                           'html5'=> false,
                         ))
                      ->add('endDate', 'date', array(
                            'widget' => 'single_text',
                            'html5'=> false,
                        ))
                      ->add('startTime', 'datetime', array(
                          'widget' => 'single_text',
                      ))
                      ->add('endTime', 'datetime', array(
                          'widget' => 'single_text',
                      ))
                      ->add('eventId','hidden');

                  /*    ->add('save','button', array('label'=> 'Continue'));*/
                        if($this->multipleSchedule)
                        {
                            if ($this->request->getMethod() != 'POST' || $frequency === 'Weekly') {
                                $builder
                                    ->add('daysOfWeek', 'choice', array(
                                        'multiple'   => true,
                                        'choices'    => array(
                                            'Sunday'    => 'SunDay',
                                            'Monday'    => 'MonDay',
                                            'Tuesday'   => 'TuesDay',
                                            'Wednesday' => 'WednesDay',
                                            'Thursday'   => 'ThursDay',
                                            'Friday'    => 'FriDay',
                                            'Saturday'  => 'SaturDay',
                                        ),
                                        'empty_data' => ''
                                    ));
                                 }
                                if ($this->request->getMethod() != 'POST' || $frequency === 'Monthly') {
                                    $builder->add('daysOfMonth', 'choice', array(
                                        'multiple' => true,
                                        'choices'     => $month_combine,
                                        'required'   => false,
                                        'empty_data'  => ''
                                    ));
                                }
                                $builder
                                    ->add('add', 'button', array('label' => 'Add Schedule'))
                                    ->add('frequency', 'choice', array(
                                    'choices'     => array(
                                        'Daily' => 'Daily',
                                        'Weekly'  => 'Weekly',
                                        'Monthly'  => 'Monthly',
                                    ),
                                    'empty_value' => 'Select Frequency',
                                    'empty_data'  => ''
                                ));
                        }

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\ScheduleMaster'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventbundle_schedule_master';
    }
}
