<?php
namespace Cogipix\CogimixCommonBundle\Utils;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface TokenStorageAwareInterface{

    function setTokenStorage( TokenStorageInterface $securityContext);
}