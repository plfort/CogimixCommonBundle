<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Cogipix\CogimixCommonBundle\Model\PlaylistConstant;

use Cogipix\CogimixCommonBundle\Model\ShareableItem;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
use Cogipix\CogimixBundle\Entity\ImportPlaylistTask;


/**
 *
 * @author plfort - Cogipix
 * @JMSSerializer\AccessType("public_method")
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\PlaylistRepository")
 * @ORM\HasLifecycleCallbacks
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Playlist implements ShareableItem
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","export","playlist_detail"})
     * @Assert\NotBlank( groups={"Create","Edit"});
     * @Assert\Length(min=2, max=40,minMessage="playlist_name_too_short", maxMessage="playlist_name_too_long", groups={"Create","Edit"})
     * @var unknown_type
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @var unknown_type
     */
    protected $shortDescription;


    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlaylistTrack",indexBy="order", mappedBy="playlist",cascade={"persist","remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     *
     * @var ArrayCollection()
     */
    protected $playlistTracks;


    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User", inversedBy="playlists")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_minimal","playlist_detail"})
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="integer",options={"default" = 0})
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @Assert\Choice(choices = {0,1,2}, groups={"Create","Edit"})
     * @var unknown_type
     */
    protected $shared = 0;

    /**
     * @ORM\Column(type="integer")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","export"})
     * @var unknown_type
     */
    protected $trackCount = 0;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $createDate;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $updateDate;
    /**
     *
     * @JMSSerializer\Expose()
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Accessor(getter="getWebPicture")
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @var unknown_type
     */
    protected $webPicture;

    /**
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $oldSharedValue = null;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="favoritePlaylists",indexBy="id")
     **/
    private $fans;

    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlaylistFolder",inversedBy="playlists")
     * @var
     */
    protected $playlistFolder;

    /**
     * @ORM\Column(type="integer")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @var unknown_type
     */
    protected $fanCount = 0;

    /**
     * @ORM\Column(name="duration", type="integer", nullable=false)
     * @var int
     */
    protected $duration = 0;

    /**
     * @ORM\OneToOne(targetEntity="Cogipix\CogimixBundle\Entity\ImportPlaylistTask",inversedBy="playlist",cascade={"remove"})
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_detail"})
     * @var ImportPlaylistTask
     */
    protected $importTask;

    /**
     * @ORM\ManyToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\Tag")
     * @ORM\JoinTable(name="playlists_tags",
     *      joinColumns={@ORM\JoinColumn(name="playlist_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @JMSSerializer\Type("array<Cogipix\CogimixCommonBundle\Entity\Tag>")
     * @var Collection
     */
    protected $tags;


    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->playlistTracks = new ArrayCollection();
        $this->fans = new ArrayCollection();
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

/*    public function getTracks()
    {
        return $this->tracks;
    }

    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
    }*/

    public function addPlaylistTrack(PlaylistTrack $playlistTrack)
    {
        $playlistTrack->setPlaylist($this);
        $this->playlistTracks->add($playlistTrack);

    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPlaylistTrack($order)
    {
        if (!isset($this->playlistTracks[$order])) {
            throw new \InvalidArgumentException(
                    "Track not found : order : " . $order);
        }
        return $this->playlistTracks[$order];
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
       // $this->oldSharedValue = $this->shared;
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
        if ($this->shared > PlaylistConstant::NOT_SHARED) {
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
        if ($this->shared > PlaylistConstant::NOT_SHARED) {
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

    public function getFans()
    {
        return $this->fans;
    }

    public function setFans($fans)
    {
        $this->fans = $fans;
    }

    public function addFan($fan)
    {
        $this->fans[$fan->getId()] = $fan;
        $this->incFanCount();
    }

    public function removeFan($fan)
    {
        if ($this->fans->containsKey($fan->getId())) {
            $this->fans->remove($fan->getId());
            $this->decFanCount();
        }
    }

    public function getFanCount()
    {
        return $this->fanCount;
    }

    public function setFanCount($fanCount)
    {
        $this->fanCount = $fanCount;
    }

    public function incFanCount()
    {
        $this->fanCount++;
    }

    public function decFanCount()
    {
        if ($this->fanCount > 0) {
            $this->fanCount--;
        }
    }

	public function getDuration() {
		return $this->duration;
	}

	public function setDuration($duration) {
		$this->duration = $duration;
		return $this;
	}

	public function increaseDuration($duration)
	{
		if($duration > 0){
			$this->duration += $duration;
		}
	}

	public function decreaseDuration($duration)
	{
		if($duration > 0){
			$this->duration -= $duration;
		}
	}

	public function getTooltip()
	{
	    date_default_timezone_set('UTC');
	    return $this->trackCount.' tracks ~ '.date("H:i:s",$this->duration);
	}

    public function getImportTask()
    {
        return $this->importTask;
    }

    public function setImportTask($importTask)
    {
        $this->importTask = $importTask;
        return $this;
    }

    public function getPlaylistTracks()
    {
        return $this->playlistTracks;
    }

    public function setPlaylistTracks($playlistTracks)
    {
        $this->playlistTracks = $playlistTracks;
        return $this;
    }

    /**
     * @JMSSerializer\VirtualProperty()
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @JMSSerializer\SerializedName("tracks")
     * @return array
     */
   public function getTracks()
    {

       return array_values($this->playlistTracks->toArray());
    }


    public function getShareableItemName()
    {
        // TODO: Implement getShareableItemName() method.
    }

    public function getImage()
    {
        return $this->getUser()->getWebPicture();
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getPlaylistFolder()
    {
        return $this->playlistFolder;
    }

    /**
     * @param mixed $playlistFolder
     */
    public function setPlaylistFolder($playlistFolder)
    {
        $this->playlistFolder = $playlistFolder;
    }





}
