<?php

namespace EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UserBundle\Entity\User;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\OneToOne(targetEntity="EventBundle\Entity\Location", mappedBy="event", cascade={"persist", "remove"})
     */
    protected $location;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDateTime", type="datetime",nullable=true)
     */
    private $createdDateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\EventBundle\Entity\EventGroup", inversedBy="event")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id",onDelete="SET NULL")
     */
    protected $group;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="text",nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;


    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    public $temp;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_page_link", type="text",nullable=true)
     */
    private $facebookPageLink;

    /**
     * @var string
     *
     * @ORM\Column(name="googlePlus_page_link", type="text",nullable=true)
     */
    private $googlePlusPageLink;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin_link", type="text",nullable=true)
     */
    private $linkedinLink;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255,nullable=true)
     */

    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="event")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\Sponsor", mappedBy="event")
     */
    protected $sponsor;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\News", mappedBy="event")
     */
    protected $news;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\Speaker", mappedBy="event")
     */
    protected $speaker;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\Ticket", mappedBy="event")
     */
    protected $ticket;

    /**
     * @ORM\OneToOne(targetEntity="EventBundle\Entity\ScheduleMaster", mappedBy="event")
     */
    protected $scheduleMaster;

    /**
     * @ORM\OneToMany(targetEntity="\EventBundle\Entity\ScheduleDetail", mappedBy="event")
     */
    protected $scheduleDetail;

    /**
     * @var boolean
     * @ORM\Column(name="has_sponsor",type="boolean",nullable=true)
     */
    protected $hasSponsor =false;

    /**
     * @var boolean
     * @ORM\Column(name="has_speaker",type="boolean",nullable=true)
     */

    protected $hasSpeaker =false;

    /**
     * @var boolean
     * @ORM\Column(name="has_news",type="boolean",nullable=true)
     */

    protected $hasNews =false;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255,nullable=true)
     */

    private $privacy;

    /**
     * @ORM\OneToOne(targetEntity="EventBundle\Entity\EventView", mappedBy="event", cascade={"persist", "remove"})
     */
    private $view;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255,nullable=true)
     */
    private $status;

    /**
     * @var boolean
     * @ORM\Column(name="is_completed",type="boolean",nullable=true)
     */

    protected $isCompleted =false;

    public function __construct()
    {
        $this->speaker = new ArrayCollection();
        $this->sponsor = new ArrayCollection();
        $this->news = new ArrayCollection();
    }

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
     * Set id
     *
     * @param integer $id
     * @return Event
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Event
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
     * Set createdDateTime
     *
     * @param \DateTime $createdDateTime
     * @return Event
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;

        return $this;
    }

    /**
     * Get createdDateTime
     *
     * @return \DateTime
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set contact
     *
     * @param string $contact
     * @return Event
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();

        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir() . '/' . $this->path;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(),$this->getFile()->getClientOriginalName());

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
    /**
     * @return EventGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param EventGroup $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return Sponsor
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }


    /**
     * @return News
     */
    public
    function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @return Speaker
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @return boolean
     */
    public function isHasSponsor()
    {
        return $this->hasSponsor;
    }

    /**
     * @return boolean
     */
    public function isHasSpeaker()
    {
        return $this->hasSpeaker;
    }

    /**
     * @return boolean
     */
    public function isHasNews()
    {
        return $this->hasNews;
    }

    /**
     * @param boolean $hasNews
     */
    public function setHasNews($hasNews)
    {
        $this->hasNews = $hasNews;
    }

    /**
     * @param boolean $hasSpeaker
     */
    public function setHasSpeaker($hasSpeaker)
    {
        $this->hasSpeaker = $hasSpeaker;
    }

    /**
     * @param boolean $hasSponsor
     */
    public function setHasSponsor($hasSponsor)
    {
        $this->hasSponsor = $hasSponsor;
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

    /**
     * Set status
     *
     * @param string $status
     * @return Event
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getFacebookPageLink()
    {
        return $this->facebookPageLink;
    }

    /**
     * @param string $facebookPageLink
     */
    public function setFacebookPageLink($facebookPageLink)
    {
        $this->facebookPageLink = $facebookPageLink;
    }

    /**
     * @return string
     */
    public function getLinkedinLink()
    {
        return $this->linkedinLink;
    }

    /**
     * @param string $linkedinLink
     */
    public function setLinkedinLink($linkedinLink)
    {
        $this->linkedinLink = $linkedinLink;
    }

    /**
     * @return string
     */
    public function getGooglePlusPageLink()
    {
        return $this->googlePlusPageLink;
    }

    /**
     * @param string $googlePlusPageLink
     */
    public function setGooglePlusPageLink($googlePlusPageLink)
    {
        $this->googlePlusPageLink = $googlePlusPageLink;
    }


    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param string $privacy
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
    }

    /**
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param ScheduleMaster $scheduleMaster
     */
    public function setScheduleMaster($scheduleMaster)
    {
        $this->scheduleMaster = $scheduleMaster;
    }
    /**
     * @return ScheduleMaster
     */
    public function getScheduleMaster()
    {
        return $this->scheduleMaster;
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

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
    /**
     * @return EventView
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param EventView $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @param boolean $isCompleted
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

}


