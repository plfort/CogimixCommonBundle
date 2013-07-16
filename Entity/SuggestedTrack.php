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
     * @ORM\Column(type="text")
     * @var unknown_type
     */
    protected $result;

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
        return gzuncompress(base64_decode($this->result));

    }

    public function setResult($result)
    {
        //$this->results = $results;
        $this->result = base64_encode(gzcompress($result, 9));
    }

}
