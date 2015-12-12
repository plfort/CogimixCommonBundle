<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 07/11/15
 * Time: 01:18
 */

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class PlaylistCreatedFeed
 * @package Cogipix\CogimixCommonBundle\Entity
 */
class PlaylistCreatedFeed extends Feed{


    /**
     * @ORM\OneToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Playlist")
     * @var Playlist
     */
    protected $playlist;

}