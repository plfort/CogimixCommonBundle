<?php

namespace Cogipix\CogimixCommonBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers the additional validators according to the storage
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ServicesCompiler implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {

     $pluginProviderDefinition = $container->findDefinition('cogimix.plugin_provider');
     $taggedPlugins = $container->findTaggedServiceIds( 'cogimix.plugin');

        foreach ($taggedPlugins as $id => $tagAttributes) {

                $pluginProviderDefinition->addMethodCall(
                        'addPlugin',
                        array(new Reference($id))
                );
        }
        $taggedPluginProviders = $container->findTaggedServiceIds( 'cogimix.plugin_provider');

        foreach ($taggedPluginProviders as $id => $tagAttributes) {

            $pluginProviderDefinition->addMethodCall(
                    'addPluginProvider',
                    array(new Reference($id))
            );
        }

        $loggerAwareServices = $container->findTaggedServiceIds('logger_aware');
        $loggerReference = new Reference('logger');
        foreach ($loggerAwareServices as $id => $tagAttributes) {
            $serviceDefinition = $container->findDefinition($id);
            $serviceDefinition->addMethodCall(
                    'setLogger',
                    array($loggerReference)
            );

        }

        $securityContextAwareServices = $container->findTaggedServiceIds('securitycontext_aware');
        $securityContextReference = new Reference('security.context');
        foreach ($securityContextAwareServices as $id => $tagAttributes) {
            $serviceDefinition = $container->findDefinition($id);
            $serviceDefinition->addMethodCall(
                    'setSecurityContext',
                    array($securityContextReference)
            );
        }
        //playlist view hook
        $playlistRendererListDefinition = $container->findDefinition('cogimix.playlist_renderer');

        $taggedServices = $container->findTaggedServiceIds( 'cogimix.playlist_renderer');

        foreach ($taggedServices as $id => $tagAttributes) {
            $playlistRendererListDefinition->addMethodCall(
                    'addPlaylistRenderer',
                    array(new Reference($id))
            );
        }
        //menu view hook
        $menuRendererListDefinition = $container->findDefinition('cogimix.menu_renderer');
        $taggedServices = $container->findTaggedServiceIds( 'cogimix.menu_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $menuRendererListDefinition->addMethodCall(
                    'addMenuRenderer',
                    array(new Reference($id))
            );
        }

        //modal view hook
        $modalRendererListDefinition = $container->findDefinition('cogimix.modal_renderer');
        $taggedServices = $container->findTaggedServiceIds( 'cogimix.modal_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $modalRendererListDefinition->addMethodCall(
                    'addModalRenderer',
                    array(new Reference($id))
            );
        }

        //js import view hook
        $jsImportRendererListDefinition = $container->findDefinition('cogimix.jsimport_renderer');
        $taggedServices = $container->findTaggedServiceIds( 'cogimix.jsimport_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $jsImportRendererListDefinition->addMethodCall(
                    'addJavascriptImportRenderer',
                    array(new Reference($id))
            );
        }

        //css import view hook
        $cssImportRendererListDefinition = $container->findDefinition('cogimix.cssimport_renderer');
        $taggedServices = $container->findTaggedServiceIds( 'cogimix.cssimport_renderer');
        foreach ($taggedServices as $id => $tagAttributes) {
            $cssImportRendererListDefinition->addMethodCall(
                    'addCssImportRenderer',
                    array(new Reference($id))
            );
        }
    }
}
