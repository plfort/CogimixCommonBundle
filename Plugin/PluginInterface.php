<?php
namespace Cogipix\CogimixCommonBundle\Plugin;

use Cogipix\CogimixCommonBundle\MusicSearch\MusicSearchInterface;

interface PluginInterface extends MusicSearchInterface{

    /**
     * The plugin name
     */
     function getName();
     /**
      * Puugin alias, used as array index
      */
     function getAlias();
     /**
      * Use in doT template as data-tag="$tag"
      */
     function getResultTag();
     /**
      * Path to the default square icon for this plugin
      */
     function getDefaultIcon();
}