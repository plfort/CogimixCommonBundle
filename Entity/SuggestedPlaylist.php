<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 *
 * @author plfort - Cogipix
 * @ORM\Entity
 */
class SuggestedPlaylist extends SuggestedItem
{

    /**
     * @ORM\OneToOne(targetEntity="Playlist")
     * @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     */
    protected $playlist;

    public function getPlaylist()
    {
        return $this->playlist;
    }

    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
    }

}
