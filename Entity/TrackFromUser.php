<?php
namespace Cogipix\CogimixCommonBundle\Entity;
use Cogipix\CogimixCommonBundle\Entity\TrackResult;

class TrackFromUser extends TrackResult
{

    protected $uid;

    protected $username;

    protected $sid;

    protected $readed;

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

    public function getSid()
    {
        return $this->sid;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    public function getReaded()
    {
        return $this->readed;
    }

    public function setReaded($readed)
    {
        $this->readed = $readed;
    }

}
