<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Widget;



class WidgetRendererList {


    private $widgetRenderers=array();

    public function addWidgetRenderer($service)
    {
        if($service instanceof WidgetRendererInterface){
            $this->widgetRenderers[] = $service;
        }
    }

    public function getWidgetRenderers(){
        return $this->widgetRenderers;
    }


}