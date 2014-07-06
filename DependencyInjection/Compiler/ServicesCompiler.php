<?php
namespace Cogipix\CogimixCommonBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;


class ServicesCompiler implements CompilerPassInterface
{

    private $pluginsProviderDefinition;

    public function loadPluginsProvider(ContainerBuilder $container)
    {
        $this->pluginsProviderDefinition[] = $container->findDefinition('cogimix.plugin_provider');
        
    }

    public function addPlugin($id)
    {
        $pluginReference = new Reference($id);
        foreach ($this->pluginsProviderDefinition as $pluginProviderDefinition) {
            $pluginProviderDefinition->addMethodCall('addPlugin', array(
                $pluginReference
            ));
        }
    }

    public function addPluginProvider($id)
    {
        $pluginProviderReference = new Reference($id);
        foreach ($this->pluginsProviderDefinition as $pluginProviderDefinition) {
            $pluginProviderDefinition->addMethodCall('addPluginProvider', array(
                $pluginProviderReference
            ));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->loadPluginsProvider($container);
        
        $taggedPlugins = $container->findTaggedServiceIds('cogimix.plugin');
        
        foreach ($taggedPlugins as $id => $tagAttributes) {
            $this->addPlugin($id);
        }
        $taggedPluginProviders = $container->findTaggedServiceIds('cogimix.plugin_provider');
        foreach ($taggedPluginProviders as $id => $tagAttributes) {
            $this->addPluginProvider($id);
        }
        $urlSearchDefinition = $container->findDefinition('cogimix.url_search');
        $taggedUrlSearchers = $container->findTaggedServiceIds('cogimix.url_search');
        foreach ($taggedUrlSearchers as $id => $tagAttributes) {
            
            $urlSearchDefinition->addMethodCall('addUrlSearcher', array(
                new Reference($id)
            ));
        }
        
        $loggerAwareServices = $container->findTaggedServiceIds('logger_aware');
        $loggerReference = new Reference('logger');
        foreach ($loggerAwareServices as $id => $tagAttributes) {
            $serviceDefinition = $container->findDefinition($id);
            $serviceDefinition->addMethodCall('setLogger', array(
                $loggerReference
            ));
        }
        
        $securityContextAwareServices = $container->findTaggedServiceIds('securitycontext_aware');
        $securityContextReference = new Reference('security.context');
        foreach ($securityContextAwareServices as $id => $tagAttributes) {
            $serviceDefinition = $container->findDefinition($id);
            $serviceDefinition->addMethodCall('setSecurityContext', array(
                $securityContextReference
            ));
        }
        // playlist view hook
        $playlistRendererListDefinition = $container->findDefinition('cogimix.playlist_renderer');
        
        $taggedServices = $container->findTaggedServiceIds('cogimix.playlist_renderer');
        
        foreach ($taggedServices as $id => $tagAttributes) {
            $playlistRendererListDefinition->addMethodCall('addPlaylistRenderer', array(
                new Reference($id)
            ));
        }
        // menu view hook
        $menuRendererListDefinition = $container->findDefinition('cogimix.menu_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.menu_item');
        foreach ($taggedServices as $id => $tagAttributes) {
            $menuRendererListDefinition->addMethodCall('addMenuRenderer', array(
                new Reference($id)
            ));
        }

        // modal view hook
        $modalRendererListDefinition = $container->findDefinition('cogimix.modal_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.modal_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $modalRendererListDefinition->addMethodCall('addModalRenderer', array(
                new Reference($id)
            ));
        }
        
        // js import view hook
        $jsImportRendererListDefinition = $container->findDefinition('cogimix.jsimport_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.jsimport_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $jsImportRendererListDefinition->addMethodCall('addJavascriptImportRenderer', array(
                new Reference($id)
            ));
        }
        
        // css import view hook
        $cssImportRendererListDefinition = $container->findDefinition('cogimix.cssimport_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.cssimport_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $cssImportRendererListDefinition->addMethodCall('addCssImportRenderer', array(
                new Reference($id)
            ));
        }
        
        // widget import view hook
        $widgetRendererListDefinition = $container->findDefinition('cogimix.widget_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.widget_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $widgetRendererListDefinition->addMethodCall('addWidgetRenderer', array(
                new Reference($id)
            ));
        }
        
        // dot template import view hook
        $dotTemplateRendererListDefinition = $container->findDefinition('cogimix.dot_renderer');
        $taggedServices = $container->findTaggedServiceIds('cogimix.dot_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $dotTemplateRendererListDefinition->addMethodCall('addDotTemplateRenderer', array(
                new Reference($id)
            ));
        }

    }
}
