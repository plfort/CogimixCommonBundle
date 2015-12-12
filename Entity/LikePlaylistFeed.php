<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 07/11/15
 * Time: 01:14
 */

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class LikePlaylistFeed
 * @package Cogipix\CogimixCommonBundle\Entity
 * @ORM\Entity()
 */
class LikePlaylistFeed extends Feed {

    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Playlist")
     * @var Playlist
     */
    protected $playlist;

    /**
     * @return Playlist
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @param Playlist $playlist
     */
    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
    }


}