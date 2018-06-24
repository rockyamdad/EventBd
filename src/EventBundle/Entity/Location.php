<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="locations")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\LocationRepository")
 */
class Location
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
     * @ORM\Column(name="venue_name", type="string", length=255, nullable=true)
     */
    private $venueName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=50 , nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=50 , nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=50 , nullable=true)
     */
    private $longitude;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Country", inversedBy="location",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="country")
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity="EventBundle\Entity\Event", inversedBy="location",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="event")
     */
    protected $event;

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
     * Set venueName
     *
     * @param string $venueName
     * @return Location
     */
    public function setVenueName($venueName)
    {
        $this->venueName = $venueName;

        return $this;
    }

    /**
     * Get venueName
     *
     * @return string 
     */
    public function getVenueName()
    {
        return $this->venueName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Location
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return Location
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set country
     *
     * @param integer $country
     * @return Location
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return integer 
     */
    public function getCountry()
    {
        return $this->country;
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
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
    public function setEventId($id) {
        $this->_eventId = $id;
    }

    public function getEventId() {
        return null == $this->getEvent() ? $this->_eventId : $this->getEvent()->getId();
    }
}
