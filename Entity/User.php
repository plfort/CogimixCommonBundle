<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 *
 *
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\UserRepository")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class User extends BaseUser
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_info","user_minimal"})
     */
    protected $id;

    /**
     * Override $user so that we can apply custom validation.
     *
     * @Assert\NotBlank(groups={"Registration","Profile"})
     * @Assert\Length(min=4, max=20,minMessage="Username is too short", maxMessage="Username is too long", groups={"Registration","Profile"})
     * @Assert\Regex(pattern="/^\w*$/",message="error_alphanum", groups={"Registration","Profile"})
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_info","user_minimal"})
     */
    protected $username;

    /**
     * Override $email so that we can apply custom validation.
     *
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(min="5", minMessage="Email is too short", maxMessage="Email is too long", groups={"Registration"})
     * @Assert\Email(groups={"Registration","Profile"})
     * @JMSSerializer\Exclude()
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="Playlist",mappedBy="user", cascade={"remove"})
     * @JMSSerializer\Groups({"user_playlists"})
     */
    protected $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="Playlist",inversedBy="fans",indexBy="id")
     * @ORM\JoinTable(name="fans_playlists")
     * @JMSSerializer\Groups({"user_favorite_playlists"})
     */
    protected $favoritePlaylists;

    /**
     * @ORM\Column(type="integer");
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_info"})
     * @var unknown_type
     */
    protected $playlistCount = 0;

    /**
     * @ORM\OneToOne(targetEntity="UserPicture", inversedBy="user", cascade={"remove","persist"})
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"user_info"})
     */
    protected $picture;

    /**
     * @ORM\OneToMany(targetEntity="Listener", mappedBy="fromUser")
     * @JMSSerializer\Exclude()
     */
    private $myListenings;

    /**
     * @ORM\OneToMany(targetEntity="Listener", mappedBy="toUser")
     * @JMSSerializer\Exclude()
     */
    private $listeners;

    /**
     * @ORM\Column(type="string",nullable=true);
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    private $shortDescription;

    public function __construct()
    {
        parent::__construct();
        $this->playlists = new ArrayCollection();
        $this->favoritePlaylists = new ArrayCollection();
        $this->myListenings = new ArrayCollection();
        $this->listeners = new ArrayCollection();
        $this->picture = new UserPicture();
        // your own logic
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        //parent::setUsername($email);
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

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function getMyListenings()
    {
        return $this->myListenings;
    }

    public function setMyListenings($myListenings)
    {
        $this->myListenings = $myListenings;
    }

    public function addListening($listening)
    {
        $this->myListenings->add($listening);
    }

    public function getListeners()
    {
        return $this->listeners;
    }

    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
    }

    public function addListener($listener)
    {
        $this->listeners->add($listener);
    }

    public function getPlaylistCount()
    {
        return $this->playlistCount;
    }

    public function setPlaylistCount($playlistCount)
    {
        $this->playlistCount = $playlistCount;
    }

    public function incPlaylistCount()
    {
        $this->playlistCount++;
    }

    public function decPlaylistCount()
    {
        if ($this->playlistCount > 0) {
            $this->playlistCount--;
        }
    }

    public function getWebPicture()
    {
        if ($this->picture == null) {
            return UserPicture::getDefaultImage();
        }
        return $this->picture->getWebPath();
    }

    public function getFavoritePlaylists()
    {
        return $this->favoritePlaylists;
    }

    public function setFavoritePlaylists($favoritePlaylists)
    {
        $this->favoritePlaylists = $favoritePlaylists;
    }

    public function addFavoritePlaylist($favoritePlaylist)
    {
        $favoritePlaylist->addFan($this);
        $this->favoritePlaylists[$favoritePlaylist->getId()] = $favoritePlaylist;
    }

    public function removeFavoritePlaylist($favoritePlaylist){
        if($this->favoritePlaylists->containsKey($favoritePlaylist->getId())){
            $favoritePlaylist->removeFan($this);
            $this->favoritePlaylists->remove($favoritePlaylist->getId());
        }
    }

}
