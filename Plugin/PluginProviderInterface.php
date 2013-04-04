<?php
namespace Cogipix\CogimixCommonBundle\Plugin;

interface PluginProviderInterface  {

    public function getAvailablePlugins();
    public function getAlias();
    public function getPluginChoiceList();
}