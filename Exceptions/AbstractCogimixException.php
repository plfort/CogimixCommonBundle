<?php
namespace Cogipix\CogimixCommonBundle\Exceptions;
abstract class AbstractCogimixException extends \Exception
{
    public $messageParameters=array();
    public $errorCode;


    public function toArray(){
        return  array('message'=>$this->message,'messageParameters'=>$this->messageParameters,'errorCode'=>$this->errorCode);
    }

    public function getMessageParameters(){
        return $this->messageParameters;
    }

    public function addParameter($key,$value){
        $this->messageParameters[$key]=$value;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function setErrorCode($errorCode) {
        $this->errorCode = $errorCode;
    }
}
