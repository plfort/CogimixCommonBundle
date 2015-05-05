<?php
namespace Cogipix\CogimixCommonBundle\Tests\Utils;

use Cogipix\CogimixCommonBundle\Entity\Song;

class SongFactory
{

    public static function generateFakeSong($title = null, $artist = null, $duration = null, $entryId = null, $shareable = null, $tag = null, $thumbnails = null)
    {
        $generator = \Faker\Factory::create();
        $song = new Song();
        $song->setTitle($title == null ? $generator->name : $title);
        $song->setArtist($artist == null ? $generator->name : $artist);
        $song->setDuration($duration == null ? $generator->numberBetween(30, 500) : $duration);
        $song->setEntryId($entryId == null ? $generator->lexify("***********") : $entryId);
        $song->setShareable($shareable == null ? $generator->boolean() : $shareable);
        $song->setTag($tag == null ? $generator->lexify("??????") : $tag);
        $song->setThumbnails($thumbnails == null ? $generator->url : $thumbnails);
        return $song;
    }
}