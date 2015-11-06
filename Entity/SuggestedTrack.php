<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\SuggestedTrackRepository")
 * @author plfort - Cogipix
 *
 */
class SuggestedTrack extends SuggestedItem
{

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $tag;

    /**
     * @ORM\Column(type="string")
     * @JMSSerializer\SerializedName("entryId")
     * @var unknown_type
     */
    protected $entryId;

    /**
     * @ORM\Column(type="text",nullable=true)
     * @var unknown_type
     */
    protected $result;

    /**
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"suggestion"})
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Song", inversedBy="suggestedTracks")
     * @var Song
     */
    protected $song;

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getEntryId()
    {
        return $this->entryId;
    }

    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;
    }

    public function getResult()
    {
        if($this->result){
            return gzuncompress(base64_decode($this->result));
        }
        return null;

    }

    public function setResult($result)
    {
        //$this->results = $results;
        $this->result = base64_encode(gzcompress($result, 9));
    }

    /**
     * @return Song
     */
    public function getSong()
    {
        return $this->song;
    }

    /**
     * @param Song $song
     */
    public function setSong($song)
    {
        $this->song = $song;
    }




}
