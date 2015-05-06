<?php
namespace Cogipix\CogimixCommonBundle\Plugin;

interface PluginProviderInterface  {

    /**
     * @return PluginInterface[]
     */
    public function getAvailablePlugins();
    public function getAlias();
    public function getPluginChoiceList();
}