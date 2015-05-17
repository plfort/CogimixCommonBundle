<?php
namespace Cogipix\CogimixCommonBundle\Entity;

use JMS\Serializer\Annotation as JMSSerializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="playlist_track")
 * @ORM\Entity()
 * @JMSSerializer\AccessType("public_method")
 * @JMSSerializer\ExclusionPolicy("all")
 * @author plfort - Cogipix
 *
 */
class PlaylistTrack
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\SerializedName("playlist_track_id")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_detail"})
     * @JMSSerializer\ReadOnly()
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Playlist", inversedBy="playlistTracks")
     * @ORM\JoinColumn(name="playlist_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $playlist;

    /**
     * @JMSSerializer\Expose()
     * @JMSSerializer\Inline()
     * @JMSSerializer\Groups({"playlist_detail","export"})
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Song", inversedBy="playlistTracks",cascade={"persist"},fetch="EAGER")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $song;

    /**
     * @JMSSerializer\Expose()
     * @ORM\Column( name="trackOrder",type="integer", nullable=false)
     * @JMSSerializer\Groups({"export","playlist_detail"})
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @var unknown
     */
    protected $addedBy;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;


    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = $this->created;
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

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Playlist
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
        return $this;
    }

    public function getSong()
    {
        return $this->song;
    }

    public function setSong($song)
    {
        $this->song = $song;
        return $this;
    }

    public function getAddedBy()
    {
        return $this->addedBy;
    }

    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }








}