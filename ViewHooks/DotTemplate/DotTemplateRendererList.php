<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\DotTemplate;



class DotTemplateRendererList {


    private $dotTemplateItems=array();

    public function addDotTemplateRenderer($service)
    {
        if($service instanceof DotTemplateInterface){
            $this->dotTemplateItems[] = $service;
        }
    }

    public function getDotTemplateRenderers(){
        return $this->dotTemplateItems;
    }


}