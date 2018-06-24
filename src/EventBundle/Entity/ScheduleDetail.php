<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduleDetail
 *
 * @ORM\Table(name="schedule_details")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ScheduleDetailRepository")
 */
class ScheduleDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time",nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="time",nullable=true)
     */
    private $endTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scheduleDate", type="date",nullable=true)
     */
    private $scheduleDate;

    /**
     * @ORM\ManyToOne(targetEntity="\EventBundle\Entity\Event", inversedBy="scheduleDetail")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id",nullable=true)
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="\EventBundle\Entity\ScheduleMaster", inversedBy="scheduleDetail")
     * @ORM\JoinColumn(name="schedule_master_id", referencedColumnName="id",nullable=true)
     */
    protected $master;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return ScheduleDetail
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return ScheduleDetail
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set scheduleDate
     *
     * @param \DateTime $scheduleDate
     * @return ScheduleDetail
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    /**
     * Get scheduleDate
     *
     * @return \DateTime 
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }
    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
    /**
     * @return ScheduleMaster
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @param mixed $master
     */
    public function setMaster($master)
    {
        $this->master = $master;
    }
}
