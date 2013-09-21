<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Menu;



class MenuRendererList {


    private $menuItems=array();
    private $menuItemsAlwaysDisplay=array();

    public function addMenuRenderer($service)
    {
        if($service instanceof MenuItemInterface){
            $this->menuItems[] = $service;
        }
        if($service instanceof MenuItemAlwaysDisplayInterface){
             $this->menuItemsAlwaysDisplay[] = $service;
        }
    }

    public function getMenuRenderers(){
        return $this->menuItems;
    }

    public function getMenuAlwaysDisplayRenderers(){
        return $this->menuItemsAlwaysDisplay;
    }


}