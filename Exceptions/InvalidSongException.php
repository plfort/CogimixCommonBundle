<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 06/05/15
 * Time: 22:58
 */

namespace Cogipix\CogimixCommonBundle\Exceptions;


class InvalidSongException extends AbstractCogimixException {

    protected $song;
    public function __construct($song){
        $this->song = $song;
        $this->message="cogimix.error.invalid_song";

    }

    public function __toString()
    {
        return __CLASS__.': ['.$this->code.'] '.$this->message.' '.$this->song;
    }



}