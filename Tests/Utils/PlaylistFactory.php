<?php
namespace Cogipix\CogimixCommonBundle\Tests\Utils;

use Cogipix\CogimixCommonBundle\Entity\Playlist;
class PlaylistFactory
{
    public static function generatePlaylist($user)
    {
        $generator = \Faker\Factory::create();
        $playlist = new Playlist();
        $playlist->setCreateDate($generator->dateTime);
        $playlist->setUpdateDate($generator->dateTime);
        $playlist->setDuration(0);
        $playlist->setName($generator->name);
        $playlist->setUser($user);
        return $playlist;
    }
}