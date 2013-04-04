<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Modal;



class ModalRendererList {


    private $modals=array();

    public function addModalRenderer($service)
    {
        if($service instanceof ModalItemInterface){
            $this->modals[] = $service;
        }
    }

    public function getModalRenderers(){
        return $this->modals;
    }


}