<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduleMaster
 *
 * @ORM\Table(name="schedule_master")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ScheduleMasterRepository")
 */
class ScheduleMaster
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
     * @ORM\Column(name="startDate", type="date",nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date",nullable=true)
     */
    private $endDate;

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
     * @var boolean
     *
     * @ORM\Column(name="hasRecurring", type="boolean",nullable=true)
     */
    private $hasRecurring;

    /**
     * @var string
     *
     * @ORM\Column(name="frequency", type="string", length=255,nullable=true)
     */
    private $frequency;

    /**
     * @var string
     *
     * @ORM\Column(name="daysOfWeek", type="string", length=255,nullable=true)
     */
    private $daysOfWeek;

    /**
     * @var string
     *
     * @ORM\Column(name="daysOfMonth", type="string", length=255,nullable=true)
     */
    private $daysOfMonth;

    /**
     * @ORM\OneToOne(targetEntity="\EventBundle\Entity\Event", inversedBy="scheduleMaster")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id",nullable=true)
     */
    protected $event;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\ScheduleDetail", mappedBy="master",cascade={"persist", "remove"})
     */
    protected $scheduleDetail;

    private $_eventId;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return ScheduleMaster
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return ScheduleMaster
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return ScheduleMaster
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
     * @return ScheduleMaster
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
     * Set hasRecurring
     *
     * @param boolean $hasRecurring
     * @return ScheduleMaster
     */
    public function setHasRecurring($hasRecurring)
    {
        $this->hasRecurring = $hasRecurring;

        return $this;
    }

    /**
     * Get hasRecurring
     *
     * @return boolean 
     */
    public function getHasRecurring()
    {
        return $this->hasRecurring;
    }

    /**
     * Set frequency
     *
     * @param string $frequency
     * @return ScheduleMaster
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return string 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set daysOfWeek
     *
     * @param string $daysOfWeek
     * @return ScheduleMaster
     */
    public function setDaysOfWeek($daysOfWeek)
    {
        $this->daysOfWeek = $daysOfWeek;

        return $this;
    }

    /**
     * Get daysOfWeek
     *
     * @return string 
     */
    public function getDaysOfWeek()
    {
        return $this->daysOfWeek;
    }

    /**
     * Set daysOfMonth
     *
     * @param string $daysOfMonth
     * @return ScheduleMaster
     */
    public function setDaysOfMonth($daysOfMonth)
    {
        $this->daysOfMonth = $daysOfMonth;

        return $this;
    }

    /**
     * Get daysOfMonth
     *
     * @return string 
     */
    public function getDaysOfMonth()
    {
        return $this->daysOfMonth;
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
     * @param ScheduleDetail $scheduleDetail
     */
    public function setScheduleDetail($scheduleDetail)
    {
        $this->scheduleDetail = $scheduleDetail;
    }
    /**
     * @return ScheduleDetail
     */
    public function getScheduleDetail()
    {
        return $this->scheduleDetail;
    }

    public function setEventId($id) {
        $this->_eventId = $id;
    }

    public function getEventId() {
        return null == $this->getEvent() ? $this->_eventId : $this->getEvent()->getId();
    }
}
