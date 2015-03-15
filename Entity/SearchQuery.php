<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity
 * @author plfort - Cogipix
 *
 */
class SearchQuery
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="searchQueries")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string",length=250)
     * @var unknown
     */
    protected $searchQuery;

    /**
     * @ORM\Column(type="json_array")
     * @var array
     */
    protected $services;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $date;

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    public function setSearchQuery($searchQuery)
    {
        $this->searchQuery = $searchQuery;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setServices(array $services)
    {
        $this->services = $services;
        return $this;
    }






}