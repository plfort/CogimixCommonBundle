<?php
namespace Cogipix\CogimixCommonBundle\Exceptions;
use Cogipix\CogimixCommonBundle\Exceptions\AbstractCogimixException;




class WarningBlockListeningException extends AbstractCogimixException{


    public function __construct(){
        $this->message="warning_blocking_listening_will_remove_it";

    }

}