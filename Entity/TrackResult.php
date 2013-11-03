<?php

namespace Cogipix\CogimixCommonBundle\Entity;
use JMS\Serializer\Annotation as JMSSerializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="track")
 * @JMSSerializer\AccessType("public_method")
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\TrackRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TrackResult
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column( name="trackOrder",type="integer", nullable=false)
     * @JMSSerializer\Groups({"export"})
     */
    protected $order;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $title;
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $artist = '';
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\SerializedName("entryId")
     * @var unknown_type
     */
    protected $entryId;
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $thumbnails;

    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Playlist", inversedBy="tracks")
     * @var unknown_type
     * @JMSSerializer\Exclude()
     */
    protected $playlist;
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export"})
     * @var unknown_type
     */
    protected $tag;

    /**
     *
     * @ORM\Column(type="array")
     * @JMSSerializer\SerializedName("pluginProperties")
     * @var array $pluginProperties
     */
    protected $pluginProperties;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $icon;

    /**
     * @ORM\Column(type="boolean")
     * @var unknown_type
     */
    protected $shareable = true;


    /**
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $oldShareableValue = null;

    public function __construct()
    {
        $this->pluginProperties = array();
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        if (empty($title)) {
            $this->title = "Unknown title";
        } else {
            $this->title = $title;
        }
    }

    public function getEntryId()
    {
        return $this->entryId;
    }

    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        if (empty($artist)) {
            $this->artist = "Unknown artist";
        } else {
            $this->artist = $artist;
        }
    }

    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    public function setThumbnails($thumbnails)
    {
        $this->thumbnails = $thumbnails;
    }

    public function getPlaylist()
    {
        return $this->playlist;
    }

    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function incOrder()
    {
        $this->order++;
    }

    public function decOrder()
    {
        $this->order--;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getPluginProperties()
    {
        if(count($this->pluginProperties) ==0 ){
            return null;
        }
        return $this->pluginProperties;
    }

    public function addPluginProperty($key, $value)
    {
        $this->pluginProperties[$key] = $value;
    }

    public function setPluginProperties($pluginProperties)
    {
        if(is_array($pluginProperties)){
            $this->pluginProperties = $pluginProperties;
        }

    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getShareable()
    {
        return $this->shareable;
    }

    public function setShareable($shareable)
    {
        $this->oldShareableValue = $this->shareable;
        $this->shareable = $shareable;
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        if ($this->shareable == true && $this->oldShareableValue == null ) {
            $this->playlist->decTrackCount();
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePresist()
    {

        if ($this->shareable) {

            $this->playlist->incTrackCount();
        }

    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {

        /*if ($this->oldShareableValue != null
                && $this->shareable != $this->oldShareableValue) {
            if ($this->shareable) {
                $this->playlist->incTrackCount();
            } else {
                $this->playlist->decTrackCount();
            }
        }*/
    }

    public function getOldShareableValue()
    {
        return $this->oldShareableValue;
    }

    public function setOldShareableValue($oldShareableValue)
    {
        $this->oldShareableValue = $oldShareableValue;
    }

    public function __toString(){
    	return trim($this->artist).' '.$this->title;
    }
}
