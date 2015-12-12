<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 07/11/15
 * Time: 01:12
 */

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class LikeSongFeed
 * @package Cogipix\CogimixCommonBundle\Entity
 * @ORM\Entity()
 *
 */
class LikeSongFeed extends  Feed {


    /**
     * @ORM\ManyToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Song")
     * @var Song
     */
    protected $song;

    /**
     * @return Song
     */
    public function getSong()
    {
        return $this->song;
    }

    /**
     * @param Song $song
     */
    public function setSong($song)
    {
        $this->song = $song;
    }




}