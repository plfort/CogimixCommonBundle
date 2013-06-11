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
 * @ORM\Entity
 */
class Playlist
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @JMSSerializer\ReadOnly()
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\TrackResult",indexBy="order", mappedBy="playlist",cascade={"persist","remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     * @JMSSerializer\Exclude()
     */
    protected $tracks;

    /** @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User", inversedBy="playlists")
     *  @JMSSerializer\Exclude()
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean",options={"default" = false})
     * @var unknown_type
     */
    protected $shared = false;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
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
        $this->shared = $shared;
    }

}
