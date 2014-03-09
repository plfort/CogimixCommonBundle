<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cogipix\CogimixCommonBundle\ViewHooks\Menu;

/**
 * Description of AbstractMenuItem
 *
 * @author pilou
 */
abstract class AbstractMenuItem implements MenuItemInterface {

    private $items = array();
    private $parameters = array();

    public function addItem(MenuItemInterface $item) {
        $this->items[] = $item;
    }

    public function addParameter($key, $value) {
        $this->parameters[$key]=$value;
    }

    public function setParameters(array $parameters){
        $this->parameters = $parameters;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function getItems(){
    	return $this->items;
    }
}
