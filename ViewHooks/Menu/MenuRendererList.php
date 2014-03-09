<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Menu;



class MenuRendererList {


    private $menuItems=array();
    private $mainMenuItems=array();

    public function addMenuRenderer($service)
    {
        if($service instanceof MenuItemInterface){
            $this->menuItems[] = $service;
        }
    }

    public function getMenuRenderers(){
        return $this->menuItems;
    }


}