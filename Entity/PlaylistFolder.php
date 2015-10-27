<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 26/10/15
 * Time: 00:40
 */

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PlaylistFolder
 * @package Cogipix\CogimixCommonBundle\Entity
 * @ORM\Entity()
 */
class PlaylistFolder {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=50)
     * @Assert\Length(min=2, max=40,minMessage="playlist_folder_name_too_short", maxMessage="playlist_folder_name_too_long", groups={"Create","Edit"})
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Cogipix\CogimixCommonBundle\Entity\Playlist",mappedBy="playlistFolder")
     * @var
     */
    protected $playlists;


    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User",inversedBy="playlistFolders")
     * @var User
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * @param mixed $playlists
     */
    public function setPlaylists($playlists)
    {
        $this->playlists = $playlists;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        return $this->name;
    }
}