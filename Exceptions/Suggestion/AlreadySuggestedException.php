<?php
namespace Cogipix\CogimixCommonBundle\Exceptions\Suggestion;
use Cogipix\CogimixCommonBundle\Exceptions\AbstractCogimixException;




class AlreadySuggestedException extends AbstractCogimixException{



    public function __construct(){
        $this->message="cogimix.already_suggested";

    }

}
