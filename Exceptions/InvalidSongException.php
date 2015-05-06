<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 06/05/15
 * Time: 22:58
 */

namespace Cogipix\CogimixCommonBundle\Exceptions;


class InvalidSongException extends AbstractCogimixException {

    public function __construct(){
        $this->message="cogimix.error.invalid_song";

    }

}