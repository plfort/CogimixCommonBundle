<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 07/11/15
 * Time: 01:15
 */

namespace Cogipix\CogimixCommonBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class NewListenerFeed
 * @package Cogipix\CogimixCommonBundle\Entity
 * @ORM\Entity()
 *
 */
class NewListenerFeed extends Feed {

    /**
     * @ORM\OneToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\Listener")
     * @var Listener
     */
    protected $listener;

    /**
     * @return Listener
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * @param Listener $listener
     */
    public function setListener($listener)
    {
        $this->listener = $listener;
    }



}