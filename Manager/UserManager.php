<?php
namespace Cogipix\CogimixCommonBundle\Manager;
use Cogipix\CogimixCommonBundle\Entity\Listener;

use Cogipix\CogimixCommonBundle\Entity\User;

use Cogipix\CogimixCommonBundle\Utils\TokenStorageAwareInterface;

use Cogipix\CogimixCommonBundle\Manager\AbstractManager;


class UserManager extends AbstractManager{




    public function findListeners($username){
        $user = $this->getCurrentUser();
        if($user != null){
         return   $this->em->getRepository('CogimixCommonBundle:User')->findByUsernameLike($user,$username);
        }
        return array();
    }



}