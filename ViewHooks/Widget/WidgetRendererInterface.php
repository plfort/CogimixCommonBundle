<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Widget;

interface WidgetRendererInterface{

    public function getWidgetTemplate();
    public function getParameters();
}