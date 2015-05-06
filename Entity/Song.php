<?php
namespace Cogipix\CogimixCommonBundle\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMSSerializer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="song",uniqueConstraints={@ORM\UniqueConstraint(name="song_unique",columns={"tag","entryId"})},
 *      indexes={
 * @ORM\Index(name="song_idx", columns={"tag","entryId"}),
 * @ORM\Index(name="song_fulltxt", columns={"artist","title"},flags="fulltext")
 * })
 * @JMSSerializer\AccessType("public_method")
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\SongRepository")
 * @UniqueEntity(fields="tag,entryId")
 * @author plfort - Cogipix
 *
 */
class Song
{
    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\Groups({"playlist_detail"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @var unknown_type
     */
    protected $title;
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @var unknown_type
     */
    protected $artist = '';

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\SerializedName("entryId")
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @var unknown_type
     */
    protected $entryId;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @var unknown_type
     */
    protected $tag;

    /**
     *
     * @ORM\Column(type="array")
     * @JMSSerializer\SerializedName("pluginProperties")
     * @JMSSerializer\Groups({"playlist_detail"})
     * @var array $pluginProperties
     */
    protected $pluginProperties;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Groups({"export","playlist_detail"})
     * @var unknown_type
     */
    protected $thumbnails;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $icon;

    /**
     * @ORM\Column(type="boolean")
     * @JMSSerializer\Groups({"playlist_detail"})
     * @var unknown_type
     */
    protected $shareable = true;

    /**
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $oldShareableValue = null;

    /**
     *
     * Duration in seconds
     * @var int
     * @ORM\Column(name="duration", type="integer", nullable=false)
     * @JMSSerializer\Groups({"playlist_detail"})
     */
    protected $duration = 180;

    /**
     * @JMSSerializer\Exclude()
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlaylistTrack",indexBy="order", mappedBy="song",cascade={"persist","remove"})
     * @var ArrayCollection
     */
    protected $playlistTracks;

    /**
     * @JMSSerializer\Exclude()
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlayedTrack",mappedBy="song")
     *
     * @var ArrayCollection<PlayedTrack>
     */
    protected $playedTracks;


    public function __construct()
    {
        $this->pluginProperties = array();
        $this->playlistTracks = new ArrayCollection();
        $this->playedTracks = new ArrayCollection();
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
        if (empty($this->artist)) {
            return "Unknown artist";
        }

        return $this->artist;
    }

    public function setArtist($artist)
    {

        $this->artist = $artist;

    }

    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    public function setThumbnails($thumbnails)
    {
        $this->thumbnails = $thumbnails;
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

    public function __toString(){
        return trim($this->getArtist()).' '.$this->title;
    }

    public function getArtistAndTitle($separator=' ')
    {
        return trim($this->artist.$separator.$this->title);
    }

    public function getDuration() {
        return $this->duration;
    }
    public function setDuration($duration) {
        $this->duration = $duration;
        return $this;
    }

    public function getOldShareableValue()
    {
        return $this->oldShareableValue;
    }

    public function setOldShareableValue($oldShareableValue)
    {
        $this->oldShareableValue = $oldShareableValue;
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

    public function addPlaylistTrack(PlaylistTrack $playlistTrack)
    {
        $playlistTrack->setSong($this);
       // $this->playlistTracks->add($playlistTrack);

    }

    /**
     * @return ArrayCollection
     */
    public function getPlayedTracks()
    {
        return $this->playedTracks;
    }

    /**
     * @param ArrayCollection $playedTracks
     */
    public function setPlayedTracks($playedTracks)
    {
        $this->playedTracks = $playedTracks;
    }



}