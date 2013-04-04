<?php
namespace Cogipix\CogimixCommonBundle\Controller;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 *
 * @author plfort - Cogipix
 *
 */
class AbstractController extends Controller
{

 protected function getCurrentUser() {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user instanceof AdvancedUserInterface){
            return $user;
        }

        return null;
    }

}
