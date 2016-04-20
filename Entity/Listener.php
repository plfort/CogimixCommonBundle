<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * @ORM\Entity
 * @ORM\Table(name="listener")
 */
class Listener
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @JMSSerializer\Groups({"suggestion","suggestion_from","feed_list"})
     * @ORM\ManyToOne(targetEntity="User", inversedBy="myListenings")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    protected $fromUser;

    /**
     * @JMSSerializer\Groups({"suggestion","suggestion_to","feed_list"})
     * @ORM\ManyToOne(targetEntity="User",inversedBy="listeners")
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    protected $toUser;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $accepted = true;

    /**
     *
     * @ORM\OneToMany(targetEntity="Suggestion", mappedBy="listener",cascade={"remove"})
     *
     */
    protected $suggestions;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {

        $this->suggestions = new ArrayCollection();
        $this->createdAt = new \DateTime();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param $fromUser
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * @return mixed
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    public function setToUser($toUser)
    {
        $this->toUser = $toUser;
    }

    public function getAccepted()
    {
        return $this->accepted;
    }

    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    }

    public function addSuggestion($suggestion)
    {
        $this->suggestions->add($suggestion);
        //$suggestion->setListener($this);
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }



}
