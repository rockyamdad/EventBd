<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventView
 *
 *@ORM\Table(name="event_views")
 *@ORM\Entity(repositoryClass="EventBundle\Repository\EventViewRepository")
 */
class EventView
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
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;
    /**
     * @ORM\OneToOne(targetEntity="EventBundle\Entity\Event", inversedBy="view",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="event")
     */
    protected $event;


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
     * Set views
     *
     * @param integer $views
     * @return EventView
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
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
}
