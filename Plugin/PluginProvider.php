<?php
namespace Cogipix\CogimixCommonBundle\Plugin;


use Symfony\Component\Security\Core\SecurityContextInterface;

class PluginProvider implements PluginProviderInterface{

    protected $plugins = array();
    protected $pluginProviders;


    public function getAvailablePlugins(){

        return $this->plugins;
    }


    public function getPluginChoiceList()
    {
        $choices = array();
        if(!empty($this->plugins)){
            foreach($this->plugins as $alias=>$plugin){
                $choices[$alias] = $plugin->getName();
            }
        }
        return $choices;
    }

    public function addPluginProvider(PluginProviderInterface $pluginProvider){
        $this->pluginProviders[$pluginProvider->getAlias()]=$pluginProvider;
        $availablePlugins = $pluginProvider->getAvailablePlugins();
        if(!empty($availablePlugins)){
            foreach($availablePlugins as $availablePlugin){
                $this->addPlugin($availablePlugin);
            }
        }
    }

    public function addPlugin(PluginInterface $plugin){
        $alias = $plugin->getAlias();
        if(!array_key_exists($alias, $this->plugins)){
            $this->plugins[$alias]=$plugin;
        }else{
            throw new \RuntimeException('A plugin with alias '.$alias.' already exists');
        }
    }


    public function getAlias(){
        return 'cogimixpluginprovider';
    }
}