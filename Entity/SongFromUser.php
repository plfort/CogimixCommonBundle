<?php
namespace Cogipix\CogimixCommonBundle\Entity;

class SongFromUser extends Song
{
    protected $uid;

    protected $username;

    protected $sid;

    protected $readed;


    public function __construct($songId,$artist,$title,$tag,$entryId,$thumbnails,$icon,$pluginProperties,$shareable,$duration,$username,$uId,$sId,$readed)
    {
        $this->setId($songId);
        $this->setArtist($artist);
        $this->setTitle($title);
        $this->setTag($tag);
        $this->setEntryId($entryId);
        $this->setThumbnails($thumbnails);
        $this->setDuration($duration);
        $this->setIcon($icon);
        $this->setShareable($shareable);
        $this->setPluginProperties($pluginProperties);


        $this->setSid($sId); // suggestion id
        $this->setReaded($readed);
        $this->setUid($uId);
        $this->setUsername($username);
    }


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
