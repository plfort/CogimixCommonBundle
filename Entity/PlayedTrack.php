<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 *@ORM\Entity
 * @author plfort - Cogipix
 *
 */
class PlayedTrack
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     *  @ORM\Column(type="string")
     * @var unknown
     */
    protected $artist;

    /**
     *  @ORM\Column(type="string")
     * @var unknown
     */
    protected $title;

    /**
     * @ORM\Column(name="duration", type="integer", nullable=false)
     * @var unknown
     */
    protected $duration=0;

    /**
     * @ORM\Column(type="string")
     * @var unknown
     */
    protected $serviceTag;

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $entryId;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="playedTracks")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(name="playDuration", type="bigint", nullable=true)
     * @var unknown
     */
    protected $playDuration;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $playDate;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    protected $stopDate;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $resumeDate;

    public function getId()
    {
        return $this->id;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    public function getServiceTag()
    {
        return $this->serviceTag;
    }

    public function setServiceTag($serviceTag)
    {
        $this->serviceTag = $serviceTag;
        return $this;
    }

    public function getEntryId()
    {
        return $this->entryId;
    }

    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getPlayDuration()
    {
        return $this->playDuration;
    }

    public function setPlayDuration($playDuration)
    {
        $this->playDuration = $playDuration;
        return $this;
    }

    public function getPlayDate()
    {
        return $this->playDate;
    }

    public function setPlayDate(\DateTime $playDate)
    {
        $this->playDate = $playDate;
        return $this;
    }

    public function getStopDate()
    {
        return $this->stopDate;
    }

    public function setStopDate(\DateTime $stopDate)
    {
        $this->stopDate = $stopDate;
        return $this;
    }

    public function getResumeDate()
    {
        return $this->resumeDate;
    }

    public function setResumeDate(\DateTime $resumeDate)
    {
        $this->resumeDate = $resumeDate;
        return $this;
    }








}