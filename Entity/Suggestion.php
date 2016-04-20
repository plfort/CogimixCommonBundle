<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 *
 * @author plfort - Cogipix
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\SuggestionRepository")
 * @ORM\Table(name="suggestion")
 * @ORM\HasLifecycleCallbacks
 */
class Suggestion
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSerializer\Groups({"suggestion","feed_list"})
     * @JMSSerializer\ReadOnly()
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Listener", inversedBy="suggestions")
     * @ORM\JoinColumn(name="listener_id", referencedColumnName="id")
     * @JMSSerializer\Groups({"suggestion","suggestion_from","suggestion_to","feed_list"})
     * @JMSSerializer\Type("Cogipix\CogimixCommonBundle\Entity\Listener")
     */
    protected $listener;

    /**
     * @ORM\ManyToOne(targetEntity="SuggestedItem", inversedBy="suggestions")
     * @ORM\JoinColumn(name="suggested_item_id", referencedColumnName="id")
     * @JMSSerializer\Type("Cogipix\CogimixCommonBundle\Entity\SuggestedItem")
     * @JMSSerializer\Groups({"suggestion","feed_list"})
     */
    protected $suggestedItem;

    /**
     * @ORM\Column(type="datetime")
     * @var unknown_type
     */

    protected $createDate;

    /**
     * @ORM\Column(type="text",length=300,nullable=true)
     * @JMSSerializer\Groups({"suggestion","feed_list"})
     * @var string
     */
    protected $originMessage;

    /**
     * @ORM\Column(type="text",length=300,nullable=true)
     * @JMSSerializer\Groups({"suggestion","feed_list"})
     * @var string
     */
    protected $responseMessage;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    protected $respondedAt;

    /**
     * @ORM\Column(type="boolean",options={"default"=false})
     *
     * @var boolean
     */
    protected $vote=false;

    /**
     * @ORM\Column(type="boolean")
     * @JMSSerializer\Groups({"suggestion","feed_list"})
     * @var boolean
     */
    protected $readed = false;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Listener
     */
    public function getListener()
    {
        return $this->listener;
    }

    public function setListener($listener)
    {
        $this->listener = $listener;
    }

    public function getSuggestedItem()
    {
        return $this->suggestedItem;
    }

    public function setSuggestedItem($suggestedItem)
    {
        $this->suggestedItem = $suggestedItem;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePresist()
    {
        $this->createDate = new \DateTime();

    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    public function getReaded()
    {
        return $this->readed;
    }

    public function setReaded($readed)
    {
        $this->readed = $readed;
    }

    /**
     * @return string
     */
    public function getOriginMessage()
    {
        return $this->originMessage;
    }

    /**
     * @param string $originMessage
     */
    public function setOriginMessage($originMessage)
    {
        $this->originMessage = $originMessage;
    }

    /**
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * @param string $responseMessage
     */
    public function setResponseMessage($responseMessage)
    {
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return boolean
     */
    public function isVote()
    {
        return $this->vote;
    }

    /**
     * @param boolean $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return \DateTime
     */
    public function getRespondedAt()
    {
        return $this->respondedAt;
    }

    /**
     * @param \DateTime $respondedAt
     */
    public function setRespondedAt($respondedAt)
    {
        $this->respondedAt = $respondedAt;
    }




}
