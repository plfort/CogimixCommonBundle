<?php
namespace Cogipix\CogimixCommonBundle\Entity;

use Cogipix\CogimixCommonBundle\Model\ShareableItem;
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
 * @JMSSerializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\SongRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"song" = "Song", "webradio" = "Cogipix\CogimixWebRadioBundle\Entity\WebRadioTrack"})
 * @UniqueEntity(fields="tag,entryId")
 * @author plfort - Cogipix
 *
 */
class Song implements ShareableItem
{

    const FLAG_NEED_CONVERT = '1';
    const FLAG_IGNORE = '-1';


    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","playlist_detail","suggestion"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","export","playlist_detail","suggestion"})
     * @var unknown_type
     */
    protected $title;
    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","export","playlist_detail","suggestion"})
     * @var unknown_type
     */
    protected $artist = '';

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\SerializedName("entryId")
     * @JMSSerializer\Groups({"song_detail","export","playlist_detail","suggestion"})
     * @var unknown_type
     */
    protected $entryId;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","export","playlist_detail","suggestion"})
     * @var unknown_type
     */
    protected $tag;

    /**
     *
     * @ORM\Column(type="array")
     * @JMSSerializer\Expose()
     * @JMSSerializer\SerializedName("pluginProperties")
     * @JMSSerializer\Groups({"song_detail","playlist_detail","suggestion"})
     * @var array $pluginProperties
     */
    protected $pluginProperties;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","export","playlist_detail","suggestion"})
     * @var unknown_type
     */
    protected $thumbnails;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var unknown_type
     */
    protected $icon;

    /**
     * @ORM\Column(type="boolean")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","playlist_detail","suggestion"})
     * @var boolean
     */
    protected $shareable = true;

    /**
     *
     * @var boolean
     */
    protected $oldShareableValue = null;

    /**
     *
     * Duration in seconds
     * @var int
     * @ORM\Column(name="duration", type="integer", nullable=false)
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","playlist_detail","suggestion"})
     */
    protected $duration = 180;

    /**
     *
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlaylistTrack",indexBy="order", mappedBy="song",cascade={"persist"})
     * @var ArrayCollection
     */
    protected $playlistTracks;

    /**
     *
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\PlayedTrack",mappedBy="song")
     *
     * @var ArrayCollection<PlayedTrack>
     */
    protected $playedTracks;

    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\SuggestedTrack",mappedBy="song")
     * @var
     */
    protected $suggestedTracks;

    /**
     * @ORM\Column(type="integer",options={"default"=0})
     * @var integer
     */
    protected $flag = 0;

    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\UserLikeSong", mappedBy="song",indexBy="id")
     **/
    protected $fans;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"song_detail","playlist_detail","suggestion"})
     * @var int
     */
    protected $fanCount = 0;


    public function __construct()
    {
        $this->pluginProperties = array();
        $this->playlistTracks = new ArrayCollection();
        $this->playedTracks = new ArrayCollection();
        $this->suggestedTracks = new ArrayCollection();
        $this->fans = new ArrayCollection();
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

    /**
     * @return ArrayCollection|PlaylistTrack[]
     */
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

    }

    /**
     * @return ArrayCollection|PlayedTrack[]
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

    /**
     * @return mixed
     */
    public function getSuggestedTracks()
    {
        return $this->suggestedTracks;
    }

    /**
     * @param mixed $suggestedTracks
     */
    public function setSuggestedTracks($suggestedTracks)
    {
        $this->suggestedTracks = $suggestedTracks;
    }

    /**
     * @return int
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param int $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    public function getFans()
    {
        return $this->fans;
    }

    public function setFans($fans)
    {
        $this->fans = $fans;
    }


    public function getFanCount()
    {
        return $this->fanCount == null ? 0 : $this->fanCount;
    }

    public function setFanCount($fanCount)
    {
        $this->fanCount = $fanCount == null ? 0 : $fanCount;
    }

    public function incFanCount()
    {
        $this->fanCount++;
    }

    public function decFanCount()
    {
        $this->fanCount--;
    }


    public function getShareableItemName()
    {
        return $this->getArtistAndTitle();
    }

    public function getImage()
    {
        return $this->getThumbnails();
    }
}