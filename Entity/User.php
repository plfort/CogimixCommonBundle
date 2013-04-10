<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Override $email so that we can apply custom validation.
     *
     * @Assert\NotBlank(groups={"CogimixRegistration"})
     * @Assert\Length(min="5", minMessage="Email is too short", maxMessage="Email is too long", groups={"CogimixRegistration"})
     * @Assert\Email(groups={"CogimixRegistration"})
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="Playlist",mappedBy="user")
     */
    protected $playlists;


    public function __construct()
    {
        parent::__construct();
        $this->playlists = new ArrayCollection();
        // your own logic
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    public function getPlaylists()
    {
        return $this->playlists;
    }

    public function setPlaylists($playlists)
    {
        $this->playlists = $playlists;
    }

    public function addPlaylist($playlist)
    {
        $this->playlists->add($playlist);
        $playlist->setUser($this);
    }

}
