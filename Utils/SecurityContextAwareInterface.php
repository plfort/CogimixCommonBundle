<?php
namespace Cogipix\CogimixCommonBundle\Utils;

use Symfony\Component\Security\Core\SecurityContextInterface;

interface SecurityContextAwareInterface{

    function setSecurityContext( SecurityContextInterface $securityContext);
}