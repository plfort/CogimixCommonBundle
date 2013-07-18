<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Cogipix\CogimixCommonBundle\Entity\TrackResult;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 *
 * @author plfort - Cogipix
 * @JMSSerializer\AccessType("public_method")
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\PlaylistRepository")
 * @ORM\HasLifecycleCallbacks
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Playlist
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list"})
     * @var unknown_type
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @var unknown_type
     */
    protected $shortDescription;

    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\TrackResult",indexBy="order", mappedBy="playlist",cascade={"persist","remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     * @JMSSerializer\Exclude()
     */
    protected $tracks;

    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User", inversedBy="playlists")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_minimal"})
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean",options={"default" = false})
     * @var unknown_type
     */
    protected $shared = false;

    /**
     * @ORM\Column(type="integer")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list"})
     * @var unknown_type
     */
    protected $trackCount = 0;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var unknown_type
     */
    protected $createDate;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var unknown_type
     */
    protected $updateDate;
    /**
     *
     * @JMSSerializer\Expose()
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Accessor(getter="getWebPicture")
     * @JMSSerializer\Groups({"playlist_list"})
     * @var unknown_type
     */
    protected $webPicture;

    /**
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $oldSharedValue = null;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->oldSharedValue = $this->shared;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTracks()
    {
        return $this->tracks;
    }

    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
    }

    public function addSong(TrackResult $song, $order = null)
    {

        $song->setPlaylist($this);
        $this->tracks->add($song);

    }

    public function removeSong($id)
    {
        $this->tracks->remove($id);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
    public function updateTracksOrder()
    {
        foreach ($this->tracks as $order => $track) {
            $track->setOrder($order);
        }
    }
    public function getTrack($order)
    {
        if (!isset($this->tracks[$order])) {
            throw new \InvalidArgumentException(
                    "Track not found : order : " . $order);
        }
        return $this->tracks[$order];
    }

    public function getAlias()
    {
        return 'playlist-cogimix-' . $this->id;
    }

    public function getShared()
    {
        return $this->shared;
    }

    public function setShared($shared)
    {
        $this->oldSharedValue = $this->shared;
        $this->shared = $shared;
    }

    public function incTrackCount()
    {
        $this->trackCount++;
    }

    public function decTrackCount()
    {
        if ($this->trackCount > 0) {
            $this->trackCount--;
        }
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        if ($this->shared == true && $this->oldSharedValue == true) {
            $this->user->decPlaylistCount();
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePresist()
    {
        $this->createDate = new \DateTime();
        $this->updateDate = $this->createDate;
        if ($this->shared == true) {
            $this->user->incPlaylistCount();
        }

    }

    /**
     * @ORM\PreUpdate
     *
     */
    public function onPreUpdate()
    {
        $this->updateDate = new \DateTime();



    }

    public function getTrackCount()
    {
        return $this->trackCount;
    }

    public function setTrackCount($trackCount)
    {
        $this->trackCount = $trackCount;
    }

    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

    public function getWebPicture()
    {
        if ($this->user == null) {
            return UserPicture::getDefaultImage();
        }
        return $this->user->getWebPicture();
    }

    public function getOldSharedValue()
    {
        return $this->oldSharedValue;
    }

    public function setOldSharedValue($oldSharedValue)
    {
        $this->oldSharedValue = $oldSharedValue;
    }



}
