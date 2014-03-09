<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Menu;

interface MenuItemInterface{

    public function getMenuItemTemplate();
    
    public function getParameters();
    
    public function getName();


}