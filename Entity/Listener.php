<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * @ORM\Entity
 */
class Listener
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;

    /**
     * @JMSSerializer\Groups({"suggestion","suggestion_from"})
     * @ORM\ManyToOne(targetEntity="User", inversedBy="myListenings")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    protected $fromUser;

    /**
     * @JMSSerializer\Groups({"suggestion","suggestion_to"})
     * @ORM\ManyToOne(targetEntity="User",inversedBy="listeners")
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    protected $toUser;

    /**
     * @ORM\Column(type="boolean")
     * @var unknown_type
     */
    protected $accepted = true;

    /**
     *
     * @ORM\OneToMany(targetEntity="Suggestion", mappedBy="listener",cascade={"remove"})
     *
     */
    protected $suggestions;

    public function __construct()
    {

        $this->suggestions = new ArrayCollection();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getFromUser()
    {
        return $this->fromUser;
    }

    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
    }

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

}
