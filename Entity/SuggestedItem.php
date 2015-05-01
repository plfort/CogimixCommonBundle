<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 *
 * @author plfort - Cogipix
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"playlist" = "SuggestedPlaylist", "track" = "SuggestedTrack"})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
abstract class SuggestedItem
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\ReadOnly()
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var unknown_type
     */

    protected $createDate;

    /**
     * @ORM\OneToMany(targetEntity="Suggestion", mappedBy="suggestedItem")
     */
    protected $suggestions;

    public function __construct(){
        $this->suggestions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    public function addSuggestion($suggestion)
    {
        $this->suggestions->add($suggestion);
        //$suggestion->setSuggestedItem($this);
    }

    public function getSuggestions()
    {
        return $this->suggestions;
    }

    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;
    }


    /**
     * @ORM\PrePersist
     */
    public function onPrePresist()
    {
        $this->createDate = new \DateTime();

    }
}
