<?php

namespace Cogipix\CogimixCommonBundle;

use Cogipix\CogimixCommonBundle\DependencyInjection\Compiler\ServicesCompiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CogimixCommonBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ServicesCompiler());
    }
}
