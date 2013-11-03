<?php
namespace Cogipix\CogimixCommonBundle\Exceptions;
use Cogipix\CogimixCommonBundle\Exceptions\AbstractCogimixException;




class AlreadyInFavoriteException extends AbstractCogimixException{



    public function __construct(){
        $this->message="cogimix.already_in_favorite_playlist";

    }

}
