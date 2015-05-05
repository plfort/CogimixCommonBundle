<?php
namespace Cogipix\CogimixCommonBundle\Entity;

class PlaylistFromUser extends Playlist
{

    protected $uid;

    protected $username;



    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }


}
