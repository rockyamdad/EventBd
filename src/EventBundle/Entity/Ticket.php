<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float", nullable=true)
     */
    private $tax;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum_ticket_buy", type="integer", nullable=true)
     */
    private $maximumTicketBuy;

    /**
     * @ORM\ManyToOne(targetEntity="\EventBundle\Entity\Event", inversedBy="ticket")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @var TicketCategory
     *
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\TicketCategory", inversedBy="ticketCategory")
     * @ORM\JoinColumn(name="ticket_category")
     */
    private $ticketCategory;
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
     * Set name
     *
     * @param string $name
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Ticket
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get integer
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set tax
     *
     * @param float $tax
     * @return Ticket
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float 
     */
    public function getTax()
    {
        return $this->tax;
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
     * @return TicketCategory
     */
    public function getTicketCategory()
    {
        return $this->ticketCategory;
    }

    /**
     * @param TicketCategory $ticketCategory
     */
    public function setTicketCategory($ticketCategory)
    {
        $this->ticketCategory = $ticketCategory;
    }

    /**
     * @return int
     */
    public function getMaximumTicketBuy()
    {
        return $this->maximumTicketBuy;
    }

    /**
     * @param int $maximumTicketBuy
     */
    public function setMaximumTicketBuy($maximumTicketBuy)
    {
        $this->maximumTicketBuy = $maximumTicketBuy;
    }
    public function setEventId($id) {
        $this->_eventId = $id;
    }

    public function getEventId() {
        return null == $this->getEvent() ? $this->_eventId : $this->getEvent()->getId();
    }

}
