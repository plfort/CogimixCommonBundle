<?php
namespace Cogipix\CogimixCommonBundle\Manager;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Cogipix\CogimixCommonBundle\Utils\TokenStorageAwareInterface;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;

use Monolog\Logger;


abstract class AbstractManager implements LoggerAwareInterface,TokenStorageAwareInterface {
    /**
     *
     * @var EntityManager $em
     */
    protected $em;

    /**
     *
     * @var Logger $logger
     */
    protected $logger;
     /**
      *
      * @var TokenStorageInterface $tokenStorage
      */
    protected $tokenStorage;


    public function setObjectManager(EntityManager $om){
        $this->em=$om;
    }

    public function setLogger($logger){
        $this->logger=$logger;
    }

    public function setTokenStorage(TokenStorageInterface $tokenStorageInterface){
        $this->tokenStorage=$tokenStorageInterface;
    }

    protected function getCurrentUser() {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof AdvancedUserInterface)
            return $user;
        return null;
    }

}