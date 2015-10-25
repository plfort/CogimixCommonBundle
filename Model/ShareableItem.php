<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 03/10/15
 * Time: 14:09
 */

namespace Cogipix\CogimixCommonBundle\Model;


interface ShareableItem {


    public function getShareableItemName();

    public function getImage();
}