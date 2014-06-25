<?php
namespace Cogipix\CogimixCommonBundle\Plugin;


use Symfony\Component\Security\Core\SecurityContextInterface;

class PluginProvider implements PluginProviderInterface{

    protected $staticPlugins = array();
   
    protected $pluginProviders;


    public function getAvailablePlugins(){
        
        
        $dynamicPlugins = array();
        foreach($this->pluginProviders as $pluginProvider){
            $availablePlugins = $pluginProvider->getAvailablePlugins();
            if(!empty($availablePlugins)){
                foreach($availablePlugins as $availablePlugin){
                    $dynamicPlugins[$availablePlugin->getAlias()]=$availablePlugin;

                }
            }
        }
        return array_merge($this->staticPlugins,$dynamicPlugins);

    }


    public function getPluginChoiceList()
    {
        $choices = array();
        $plugins = $this->getAvailablePlugins();
        
        if(!empty($plugins)){
            foreach($plugins as $alias=>$plugin){
                $choices[$alias] = $plugin->getName();
            }
        }
        return $choices;
    }

    public function addPluginProvider(PluginProviderInterface $pluginProvider){
        $this->pluginProviders[$pluginProvider->getAlias()]=$pluginProvider;
 
    }

    public function addPlugin(PluginInterface $plugin){
        $alias = $plugin->getAlias();
      
        if(!array_key_exists($alias, $this->staticPlugins)){
            $this->staticPlugins[$alias]=$plugin;
        }else{
            throw new \RuntimeException('A plugin with alias '.$alias.' already exists');
        }
    }


    public function getAlias(){
        return 'cogimixpluginprovider';
    }
}