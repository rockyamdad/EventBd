<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_items")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\CartItemRepository")
 */
class CartItem
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
     * @ORM\Column(name="eventName", type="string", length=255,nullable=true)
     */
    private $eventName;

    /**
     * @var string
     *
     * @ORM\Column(name="ScheduleDate", type="string", length=255,nullable=true)
     */
    private $scheduleDate;

    /**
     * @var string
     *
     * @ORM\Column(name="scheduleTime", type="string", length=255,nullable=true)
     */
    private $scheduleTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ticketName", type="string", length=255,nullable=true)
     */
    private $ticketName;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float",nullable=true)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float",nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="userName", type="string", length=255,nullable=true)
     */
    private $userName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="buyDate", type="date",nullable=true)
     */
    private $buyDate;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="cart")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


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
     * Set eventName
     *
     * @param string $eventName
     * @return CartItem
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string 
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set scheduleDate
     *
     * @param string $scheduleDate
     * @return CartItem
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    /**
     * Get scheduleDate
     *
     * @return string 
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    /**
     * Set scheduleTime
     *
     * @param string $scheduleTime
     * @return CartItem
     */
    public function setScheduleTime($scheduleTime)
    {
        $this->scheduleTime = $scheduleTime;

        return $this;
    }

    /**
     * Get scheduleTime
     *
     * @return string 
     */
    public function getScheduleTime()
    {
        return $this->scheduleTime;
    }

    /**
     * Set ticketName
     *
     * @param string $ticketName
     * @return CartItem
     */
    public function setTicketName($ticketName)
    {
        $this->ticketName = $ticketName;

        return $this;
    }

    /**
     * Get ticketName
     *
     * @return string 
     */
    public function getTicketName()
    {
        return $this->ticketName;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return CartItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
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
     * @return CartItem
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
     * Set userName
     *
     * @param string $userName
     * @return CartItem
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set buyDate
     *
     * @param string $buyDate
     * @return CartItem
     */
    public function setBuyDate($buyDate)
    {
        $this->buyDate = $buyDate;

        return $this;
    }

    /**
     * Get buyDate
     *
     * @return string 
     */
    public function getBuyDate()
    {
        return $this->buyDate;
    }
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
