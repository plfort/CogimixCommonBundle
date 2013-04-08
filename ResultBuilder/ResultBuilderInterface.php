<?php
namespace Cogipix\CogimixCommonBundle\ResultBuilder;

interface ResultBuilderInterface{

    /**
     * Use in doT template as data-tag="$tag"
     */
    function getResultTag();
    /**
     * Path to the default square icon for this plugin
    */
    function getDefaultIcon();
}