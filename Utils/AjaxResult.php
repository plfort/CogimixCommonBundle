<?php
namespace Cogipix\CogimixCommonBundle\Utils;

use Cogipix\CogimixCommonBundle\Exceptions\AbstractCogimixException;

use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Symfony\Component\HttpFoundation\Response;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxResult
 *
 * @author pilou
 */
use Symfony\Component\Translation\TranslatorInterface;

class AjaxResult {


    protected $success;
    protected $errorMessage;
    protected $errorCode;
    protected $data;
    protected $html;

    public function __construct(){
        $this->success=false;
        $this->data=array();
    }


    public function mapCogimixException(AbstractCogimixException $ex,TranslatorInterface $trans){
        $this->success=false;
        //$this->errorCode=$ex ->getErrorCode();
        $this->errorMessage=$trans->trans($ex->getMessage(), $ex->getMessageParameters());
    }


    public function getJSON($serializer=null){
        if($serializer==null){
            $encoders = array( new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
        }
        $r=array();
        $r['success']=$this->success;
        if($this->success===false){
        	if($this->errorMessage!==null){
            $r['errorMessage']=$this->errorMessage;
        	}
        	if($this->errorCode!==null){
            $r['errorCode']=$this->errorCode;
        	}
        }
        if($this->html !==null){
             $r['html']=$this->html;
        }
        if(!empty($this->data)){
        	$r['data']=  $this->data;
        }
        return $serializer->serialize($r, 'json');
    }

    public function getSuccess() {
        return $this->success;
    }

    public function setSuccess($success) {
        $this->success = $success;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function setErrorCode($errorCode) {
        $this->errorCode = $errorCode;
    }

    public function getHtml() {
        return $this->html;
    }

    public function setHtml($html) {
        $this->html = $html;
    }


    public function addData($key,$val){
        $this->data[$key]=$val;
    }

    public function createResponse($serializer=null){

        return new Response($this->getJSON($serializer));
    }


}

?>
