<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Javascript;



class JavascriptImportRendererList {


    private $jsImportItems=array();

    public function addJavascriptImportRenderer($service)
    {
        if($service instanceof JavascriptImportInterface){
            $this->jsImportItems[] = $service;
        }
    }

    public function getJavascriptImportRenderers(){
        return $this->jsImportItems;
    }


}