<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Css;



class CssImportRendererList {


    private $cssImportItems=array();

    public function addCssImportRenderer($service)
    {
        if($service instanceof CssImportInterface){
            $this->cssImportItems[] = $service;
        }
    }

    public function getCssImportRenderers(){
        return $this->cssImportItems;
    }


}