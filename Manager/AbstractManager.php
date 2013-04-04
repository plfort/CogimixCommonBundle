<?php
namespace Cogipix\CogimixCommonBundle\Manager;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Cogipix\CogimixCommonBundle\Utils\SecurityContextAwareInterface;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;

use Doctrine\Common\Persistence\ObjectManager;


abstract class AbstractManager implements LoggerAwareInterface,SecurityContextAwareInterface {
    /**
     *
     * @var DocumentManager $dm
     */
    protected $em;

    protected $logger;

    protected $securityContext;


    public function setObjectManager(ObjectManager $om){
        $this->em=$om;
    }

    public function setLogger($logger){
        $this->logger=$logger;
    }

    public function setSecurityContext(SecurityContextInterface $securityContext){
        $this->securityContext=$securityContext;
    }

    protected function getCurrentUser() {
        $user = $this->securityContext->getToken()->getUser();
        if ($user instanceof AdvancedUserInterface)
            return $user;
        return null;
    }

}